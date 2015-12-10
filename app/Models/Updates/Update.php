<?php namespace Ankh;

use Illuminate\Support\Str;
use BadMethodCallException;
use Lang;

class Update extends Entity {

	const U_ADDED   = 1;
	const U_DELETED = 2;
	const U_RENAMED = 3;
	const U_INFO    = 4;

	const C_OLD     = 'old';
	const C_NEW     = 'new';

	protected $table = 'updates';
	protected $guarded = ['id'];
	protected $fillable = ['type', 'change'];
	public $timestamps = ['created_at'];

	protected $cached_pivot;
	protected $cached_related = [];

	public function getChangeAttribute($value) {
		return ($value != null) ? (@json_decode($value, true) ?: $value) : null;
	}

	public function setChangeAttribute($value) {
		$this->attributes['change'] = ($value != null) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
	}

	public function oldValue() {
		$change = $this->change;

		return is_array($change) ? $change[self::C_OLD] : null;
	}

	public function newValue() {
		$change = $this->change;

		return is_array($change) ? $change[self::C_NEW] : $change;
	}

	public function entity() {
		$type = $this->updateType();
		$id = $this->entityId();

		$entity = StaticCache::cached($type, $id);

		if ($type && !$entity) {
			$entity = $this->belongsToMany($this->entityClass(), 'entity_update', 'update_id', 'entity_id')
			->withPivot(['r_type'])
			->where('entity_update.r_type', $type)->withTrashed()->first();

			StaticCache::cached($type, $id, $entity);
		}

		return $entity;
	}

	public function diffString($format = '{:delta}', $colors = []) {
		return $this->changeString($format, function ($change) use ($colors) {
			$delta = intval(@$change[self::C_NEW]) - intval(@$change[self::C_OLD]);
			if (!$delta)
				return false;

			$change['delta'] = diff_size($delta);
			if ($colors)
				$change['color'] = $colors[$delta >= 0];

			return $change;
		});
	}

	public function changeString($format = null, \Closure $replacements = null) {
		$change = $this->change;

		if (is_string($change))
			$change = ['a' => $change, self::C_OLD => null, self::C_NEW => null];

		if ($replacements)
			if (!($change = $replacements($change)))
				return '';

		if (!$format) {
			$class = strtolower(class_basename($this));
			$types = [self::U_ADDED => 'add', self::U_DELETED => 'delete'];
			$type = @$types[$this->type] ?: 'change';

			$path = [$class, $type];
			if ($attr = @$change['a'])
				$path[] = $attr;

			while ($path) {
				if (Lang::has($key = "updates." . join('.', $path)))
					break;
				else
					$key = null;
				array_shift($path);
			}

			$format = Lang::get($key ?: "updates.change");
		}

		return preg_replace_callback(['"\{:([\w\d_]+)\}"', '":([\w\d_]+)"'], function ($match) use ($change) {
			return isset($change[$match[1]]) ? $change[$match[1]] : null;
		}, $format);
	}

	public function __toString() {
		switch ($this->type) {
		case self::U_ADDED:
		case self::U_DELETED:
			return $this->changeString(null, function ($change) {
				$change['delta'] = $this->diffString('({:delta})');
				return $change;
			});
		}
		return $this->changeString();
	}

	public function version() {
		$version = new Version();
		$version->setEntity($this->entity());
		$version->setTimestamp($this->created_at);
		return $version;
	}

	protected function getPivoted($column) {
		if (!$this->cached_pivot) {
			if (!$this->id)
				return 0;

			$this->cached_pivot = \DB::table('entity_update')->where('update_id', $this->id)->first(['r_type', 'entity_id']);
			if (!$this->cached_pivot) {
				debug("Err get pivot for " . $this->entityClass() . " $this->id");
				return 0;
			}
		}

		return $this->cached_pivot->{$column};
	}

	public function newQuery() {
		$query = parent::newQuery();

		if ((!($type = $this->updateType())))
			return $query;

		return $query->join('entity_update', function ($query) use ($type) {
			return $query->on('update_id', '=', 'id');
		})->where('r_type', $type);
	}

	public function updateType() {
		return $this->getPivoted('r_type');
	}

	public function entityId() {
		return $this->getPivoted('entity_id');
	}

	public function entityClass() {
		return Entity::class;
	}

	public function scopeAdded($query) {
		return $query->whereType(self::U_ADDED);
	}

	public function scopeDeleted($query) {
		return $query->whereType(self::U_DELETED);
	}

	public function scopeRenamed($query) {
		return $query->whereType(self::U_RENAMED);
	}

	public function __call($method, $args) {
		if (Str::startsWith($method, 'related')) {
			$property = lcfirst(substr($method, 7));

			if (!isset($this->cached_related[$property])) {
				$type = null;
				$related = null;
				$id = 0;
				$cached = null;
				$entity = null;

				if (($type = StaticCache::map($property)) != null) {
					$entity = $this->entity();

					if ($entity && array_search($property, get_object_vars($entity)) !== false) {
						$id = intval($entity->{"{$property}_id"});
						if (!$id) {
							if ($entity->updateType() == $type) {
								$related = $cached = $entity;
								$id = $entity->id;
							}
						}

						if (!$related)
							$related = $cached = StaticCache::cached($type, $id);
					}
				}

				if (!$related) {
					if (method_exists($this, $property))
						$related = $this->$property($args);
				}

				if (!$related) {
					if ($entity ?: $entity = $this->entity())
						$related = $entity->{$property};
				}

				if ($related && !$cached) {
					$type = ($type !== null) ? $type : $related->getModel()->updateType();
					StaticCache::map($property, $type);
					$id = $id ?: ($related ? $related->id : 0);
					StaticCache::cached($type, $id, $related);
				}

				$this->cached_related[$property] = $related;
			}

			return $this->cached_related[$property];
		}

		return parent::__call($method, $args);
	}

}


class StaticCache {

	protected static $cache = [];
	protected static $mapping = [];

	public static function map($property, $type = null) {
		if ($type !== null)
			return self::$mapping[$property] = $type;

		return isset(self::$mapping[$property]) ? self::$mapping[$property] : null;
	}

	public static function cached($k1, $k2, $value = null) {
		if ($value !== null)
			self::$cache[$k1][$k2] = $value;
		else
			$value = isset(self::$cache[$k1]) ? isset(self::$cache[$k1][$k2]) ? self::$cache[$k1][$k2] : null : null;

		return $value;
	}


}
