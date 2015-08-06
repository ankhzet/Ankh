<?php

	namespace Ankh\Traits\Entity;

	use Ankh\Contracts\OrderingDescriptor;

	trait OrderableTrait {

		public function orderWith(OrderingDescriptor $descriptor) {
			$descriptor->applyOrdering($this);
		}

	}
