<?php

	namespace Ankh\Entity\Filters;

	use Ankh\Contracts\EntityContract as Entity;
	use Ankh\Contracts\FilterContract;

	abstract class BasicFilter implements FilterContract {

		public function applyFilterToQuery($query) {
		}

		public function applyFilter(Entity $entity) {
			$entity->underlyingQuery($this->applyFilterToQuery($entity->underlyingQuery()));
		}

	}

