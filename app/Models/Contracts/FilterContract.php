<?php

	namespace Ankh\Contracts;

	use EntityContract as Entity;

	interface FilterContract {

		public function shouldApply();

		public function applyFilter(Entity $entity);

		public function applyFilterToQuery($query);


		public function paginationQueryFilter();

	}
