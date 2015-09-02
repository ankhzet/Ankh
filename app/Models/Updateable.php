<?php namespace Ankh;

use Closure;

use Ankh\Update;

class Updateable extends Entity {

	const DELETED_AT = 'deleted_at';

	protected static function boot() {
		parent::boot();

		static::attachEventListeners();
	}

	protected static function attachEventListeners() {
		static::created(function ($updateable) {
			$updateable->wasCreated();
		});

		static::updating(function($updateable) {
			if ($diff = $updateable->diffAttributes())
				$updateable->willBeUpdated($diff);
		});

		static::deleting(function ($updateable) {
			$updateable->willBeDeleted();
		});
	}

	protected function diffAttributes() {
		$timestamps = [static::CREATED_AT, static::UPDATED_AT, static::DELETED_AT];
		$original = array_except($this->getOriginal(), $timestamps);
		$dirty    = array_except($this->getAttributes(), $timestamps);

		return array_diff($dirty, $original);
	}

	protected function wasCreated(Closure $callback = null) {
	}

	protected function willBeUpdated($dirty) {
	}

	protected function willBeDeleted(Closure $callback = null) {
	}

	public function newUpdate($type, Closure $callback = null) {
		$class = $this->updateClass();
		$update = new $class;

		$update->type = $type;

		if ($callback)
			$callback($update);

		$update->save();

		$this->attachUpdate($update);
	}

	public function updates() {
		return $this->belongsToMany($this->updateClass(), 'entity_update', 'entity_id', 'update_id')
		->withPivot(['r_type'])
		->where('entity_update.r_type', $this->updateType());
	}

	public function attachUpdate($update) {
		$id = is_numeric($update) ? $update : $update->id;
		return $this->updates()->attach($id, array('r_type' =>  $this->updateType()));
	}

	protected function updateType() {
		return 0;
	}

	protected function updateClass() {
		return Update::class;
	}

}
