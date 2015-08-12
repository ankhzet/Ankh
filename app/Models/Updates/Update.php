<?php namespace Ankh;

use Illuminate\Support\Str;
use BadMethodCallException;

class Update extends Entity {

	const U_ADDED   = 1;
	const U_DELETED = 2;
	const U_RENAMED = 3;

	protected $table = 'updates';
	protected $guarded = ['id'];
	protected $fillable = ['type', 'value', 'delta', 'change'];
	public $timestamps = ['created_at'];

	protected $cached_pivot;
	protected $cached_related = [];

	public function entity() {
		$type = $this->updateType();
		$id = $this->entityId();

		$entity = StaticCache::cached($type, $id);

		if (!$entity) {
			$entity = $this->belongsToMany($this->entityClass(), 'entity_update', 'update_id', 'entity_id')
			->withPivot(['r_type'])
			->where('entity_update.r_type', $type)->withTrashed()->first();

			StaticCache::cached($type, $id, $entity);
		}

		return $entity;
	}

	public function diffString($format = ':delta', $colors = []) {
		if (!$this->delta)
			return '';

		$delta = diff_size($this->delta);
		$str = str_replace(':delta', $delta, $format);
		if ($colors)
			$str = str_replace(':color', $colors[$delta >= 0], $str);
		return $str;
	}

	protected function getPivoted($column) {
		if (!$this->cached_pivot) {
			if (!$this->id)
				return 0;

			$this->cached_pivot = \DB::table('entity_update')->where('update_id', $this->id)->first(['r_type', 'entity_id']);
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
					$related = with($entity ?: $this->entity())->{$property};
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
