<?php namespace Ankh;

class PageUpdate extends Update {
	const TYPE = 3;

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

	public function pageVersion() {
		$page = $this->relatedPage();

		return $page->version($this->created_at);
	}

	public function __toString() {
		switch ($this->type) {
		case self::U_DIFF:
			return $this->diffString('<b class="delta {:color}">{:delta}</b>', ['red', 'green']);
		case self::U_MOVED:
			return $this->changeString(null, function ($change) {
				$g = app(\Ankh\Contracts\GroupRepository::class);
				$change['new'] = $g->findEvenTrashed(intval($change['new']), ['id', 'title']);
				$change['old'] = $g->findEvenTrashed(intval($change['old']), ['id', 'title']);
				return $change;
			});
		}
		return parent::__toString();
	}

}
