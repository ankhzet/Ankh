<?php

	namespace Ankh\Traits\Entity;

	trait LayeredRepositoryTrait {
		protected $LayeredEntityRepository_query = null;

		public function underlyingQuery($setQuery = null) {
			if ($setQuery)
				$this->LayeredEntityRepository_query = $setQuery;

			return $this->LayeredEntityRepository_query ?: ($this->LayeredEntityRepository_query = $this->getQuery());
		}

		public function paginate($perPage = null) {
			return $this->underlyingQuery()->paginate($perPage);
		}

	}
