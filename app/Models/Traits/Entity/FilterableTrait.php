<?php namespace Ankh\Traits\Entity;

use Ankh\Contracts\Filter;

trait FilterableTrait {

	public function filterWith(Filter $filter) {
		$filter->applyFilter($this);
	}

}
