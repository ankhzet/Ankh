<?php

	namespace Ankh\Contracts;

	use EntityContract as Entity;

	interface OrderingDescriptorContract {

		public function applyOrdering(Entity $entity);

	}
