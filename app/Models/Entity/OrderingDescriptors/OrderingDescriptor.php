<?php

	namespace Ankh\Entity\OrderingDescriptors;

	use Ankh\Contracts\EntityContract as Entity;
	use Ankh\Contracts\OrderingDescriptorContract;

	/*
	 * Helper class for pages ordering.
	 * Default ordering is by 'title' collumn, ascending.
	 */
	class OrderingDescriptor implements OrderingDescriptorContract {
		protected $direction;
		protected $collumn;

		public function __construct($direction = 'asc', $collumn = 'title') {
			$this->direction = $direction;
			$this->collumn = $collumn;
		}

		public function applyOrdering(Entity $entity) {
			$entity->underlyingQuery()->orderBy($this->collumn, $this->direction);
		}

	}
