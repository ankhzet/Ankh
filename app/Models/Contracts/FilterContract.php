<?php

	namespace Ankh\Contracts;

	use EntityContract as Entity;

	interface FilterContract {

		public function applyFilter(Entity $entity);

		public function applyFilterToQuery($query);

	}
