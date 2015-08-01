<?php

	namespace Ankh\Contracts;

	use FilterContract as Filter;

	interface FilterableContract {

		public function filterWith(Filter $filter);

	}
