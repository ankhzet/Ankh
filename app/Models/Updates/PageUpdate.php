<?php namespace Ankh;

class PageUpdate extends Update {
	const TYPE = 3;

	const U_INFO    = 4;
	const U_DIFF    = 5;
	const U_MOVED   = 6;

	public function page() {
		return $this->entity();
	}

	public function updateType() {
		return self::TYPE;
	}

	public function entityClass() {
		return Page::class;
	}

	public function scopeDiff($query) {
		return $query->whereType(self::U_DIFF);
	}

}
