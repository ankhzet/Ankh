<?php namespace Ankh\Entity\Filters;

use Ankh\Contracts\Filter;
use Ankh\Contracts\Entity;

abstract class BasicFilter implements Filter {

	public function shouldApply() {
		return true;
	}

	public function applyFilterToQuery($query) {
	}

	public function applyFilter(Entity $entity) {
		$entity->underlyingQuery($this->applyFilterToQuery($entity->underlyingQuery()));
	}

}

