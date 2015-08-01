<?php

	namespace Ankh\Traits\Entity;

	use Ankh\Contracts\OrderingDescriptorContract as OrderingDescriptor;

	trait OrderableTrait {

		public function orderWith(OrderingDescriptor $descriptor) {
			$descriptor->applyOrdering($this);
		}

	}
