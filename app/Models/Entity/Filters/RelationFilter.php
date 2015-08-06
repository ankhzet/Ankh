<?php

	namespace Ankh\Entity\Filters;


	class RelationFilter extends BasicFilter {
		protected $relation;
		protected $id;

		public function __construct($relation, $id) {
			$this->relation = $relation;
			$this->id = $id;
		}

		public function paginationQueryFilter() {
			return $this->id;
		}

		public function applyFilterToQuery($query) {
			return $query->where("{$this->relation}_id", $this->id);
		}

	}

