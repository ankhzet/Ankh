<?php

	namespace Ankh\Contracts;

	interface EntityContract extends FilterableContract, OrderableContract {

		public function underlyingQuery($setQuery = null);

		public function paginate($perPage = null);

	}
