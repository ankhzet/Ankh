<?php namespace Ankh\Traits\Entity\Repository;

use Ankh\Entity\Filters\OffsetFilter;

trait OffsetFilterTrait {

	public function addOffsetFilter($after, $before = 0) {
		if ($after + $before)
			$this->addFilter('offset-id', new OffsetFilter($after, $before));
	}

}
