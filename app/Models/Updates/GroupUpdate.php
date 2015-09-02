<?php namespace Ankh;

class GroupUpdate extends Update {
	const TYPE = 2;

	public function group() {
		return $this->entity();
	}

	public function updateType() {
		return self::TYPE;
	}

	public function entityClass() {
		return Group::class;
	}

}
