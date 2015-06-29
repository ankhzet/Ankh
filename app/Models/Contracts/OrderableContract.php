<?php

	namespace Ankh\Contracts;

	use OrderingDescriptorContract as OrderingDescriptor;

	interface OrderableContract {

		public function orderWith(OrderingDescriptor $descriptor);

	}
