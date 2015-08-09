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
	protected $cached_entity;

	public function entity() {
		if (!$this->cached_entity) {
			$this->cached_entity = $this->belongsToMany($this->entityClass(), 'entity_update', 'update_id', 'entity_id')
			->withPivot(['r_type'])
			->where('entity_update.r_type', $this->updateType())->first();
		}
		return $this->cached_entity;
	}

	protected function getPivoted($column) {
		if (!$this->cached_pivot)
			$this->cached_pivot = \DB::table('entity_update')->where('update_id', $this->id)->first(['r_type', 'entity_id']);

		return $this->cached_pivot->{$column};
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
			$method = lcfirst(substr($method, 7));
			if (method_exists($this, $method))
				return $this->$method($args);

			$entity = $this->entity();
			try {
				return $entity->{$method};
			} catch (Exception $e) {
			}

			return null;
		}

		return parent::__call($method, $args);
	}

}
