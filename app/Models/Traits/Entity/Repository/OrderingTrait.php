<?php namespace Ankh\Traits\Entity\Repository;

use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

trait OrderingTrait {

	public function order() {
		$this->applyOrdering(new OrderingDescriptor());
	}

}
