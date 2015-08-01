<?php

	namespace Ankh\Traits\Entity;

	use Ankh\Contracts\FilterContract as Filter;

	trait FilterableTrait {

		public function filterWith(Filter $filter) {
			$filter->applyFilter($this);
		}

	}
