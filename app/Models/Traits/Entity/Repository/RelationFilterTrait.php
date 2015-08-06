<?php namespace Ankh\Traits\Entity\Repository;

use Ankh\Entity\Filters\RelationFilter;

trait RelationFilterTrait {

	public function addRelationFilter($column, $value) {
		if ($value)
			$this->addFilter($column, new RelationFilter($column, $value));
	}

}
