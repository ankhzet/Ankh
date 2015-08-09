<?php namespace Ankh;

class GroupUpdate extends Update {
	const TYPE = 2;

	const U_INFO    = 4;

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
