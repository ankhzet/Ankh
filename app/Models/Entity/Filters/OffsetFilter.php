<?php namespace Ankh\Entity\Filters;

class OffsetFilter extends BasicFilter {
	protected $after;
	protected $before;

	public function __construct($after, $before) {
		$this->after = $after;
		$this->before = $before;
	}

	public function paginationQueryFilter() {
		return "{$this->after}..{$this->before}";
	}
	public function applyFilterToQuery($query) {
		if ($this->after)
			$query = $query->where("id", '>', $this->after);
		if ($this->before)
			$query = $query->where("id", '<', $this->before);
		return $query;
	}

}

