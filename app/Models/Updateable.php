<?php namespace Ankh;

use Closure;

use Ankh\Update;

class Updateable extends Entity {

	const DELETED_AT = 'deleted_at';

	const RENAME_FIELD = 'title';

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

	public function diffAttributes() {
		$timestamps = [static::CREATED_AT, static::UPDATED_AT, static::DELETED_AT];
		$original = array_except($this->getOriginal(), $timestamps);
		$dirty    = array_except($this->getAttributes(), $timestamps);

		$diff = [];
		foreach ($dirty as $key => &$value)
			if (@$original[$key] != $value)
				$diff[$key] = $value;

		return $diff; //array_diff($dirty, $original);
	}

	protected function wasCreated(Closure $callback = null) {
		$this->newUpdate(Update::U_ADDED, $callback);
	}

	protected function infoUpdateCapture() {
		return [
			static::RENAME_FIELD => Update::U_RENAMED,
		];
	}

	protected function willBeUpdated($dirty) {
		foreach ($this->infoUpdateCapture() as $field => $type)
			$this->checkChange(ltrim($field, '-'), $dirty, $type, !(strpos($field, '-') === 0));
	}

	protected function willBeDeleted(Closure $callback = null) {
		$this->newUpdate(Update::U_DELETED, $callback);
	}

	protected function changedAttribute($attribute, array $dirty, $fromNull = false)  {
		if (!isset($dirty[$attribute]))
			return false;

		$was = $this->getOriginal($attribute);

		if (!$fromNull && $was == null)
			return false;

		return ($dirty[$attribute] != $was) ? [$was, $dirty[$attribute]] : false;
	}

	protected function pickAttr($a, array $dirty = []) {
		$old = $this->getOriginal($a);
		$new = @$dirty[$a];
		return compact('a', 'old', 'new');
	}

	protected function pickDiff($a, array $dirty, $fromNull = false) {
		$changed = $this->changedAttribute($a, $dirty, $fromNull);

		if (is_array($changed) && (list($old, $new) = $changed)) {
			return compact('a', 'old', 'new');
		}

		return [];
	}

	public function checkChange($attribute, array $dirty, $type, $capture = true, $fromNull = false) {
		if ($change = $this->pickDiff($attribute, $dirty, $fromNull)) {
			$change = $capture ? $change : $attribute;
			$this->newUpdate($type, function ($update) use ($change) {
				$update->change = $change;
			});
		}

		return !!$change;
	}

	public function newUpdate($type, Closure $callback = null) {
		$class = $this->updateClass();
		$update = new $class;

		$update->type = $type;

		if ($callback)
			$callback($update);

		$update->save();

		$this->attachUpdate($update);

		return $update;
	}

	public function updates() {
		return $this->belongsToMany($this->updateClass(), 'entity_update', 'entity_id', 'update_id')
		->withPivot(['r_type'])
		->where('r_type', $this->updateType());
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
