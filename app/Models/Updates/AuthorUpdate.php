<?php namespace Ankh;

class AuthorUpdate extends Update {
	const TYPE = 1;

	const U_INFO    = 4;

	public function author() {
		return $this->entity();
	}

	public function updateType() {
		return self::TYPE;
	}

	public function entityClass() {
		return Author::class;
	}

}
