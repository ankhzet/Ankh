<?php namespace Ankh\Contracts;

	interface Filter {

		public function shouldApply();

		public function applyFilter(Entity $entity);

		public function applyFilterToQuery($query);


		public function paginationQueryFilter();

	}
