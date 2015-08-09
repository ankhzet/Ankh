<?php namespace Ankh;

use Ankh\Update;

class Updateable extends Entity {

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
