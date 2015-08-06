<?php

	use Ankh\Contracts\Filter;
	use Ankh\Contracts\Entity;

	class LetterFilter extends BasicFilter {
		protected $letter = null;

		public function __construct($letter) {
			$this->letter($letter);
		}

		public function shouldApply() {
			return $this->letter() !== null;
		}

		public function paginationQueryFilter() {
			return $this->letter();
		}

		public function applyFilterToQuery($query) {
			$collumn = $query->getModel()->letterCollumn();
			return $query->where($collumn, 'like', "{$this->letter()}%");
		}

		public function letter($value = null) {
			if ($value !== null)
				$this->letter = mb_strtoupper($value);

			return $this->letter;
		}

		public function lettersUsage(Entity $entity, array $filters = []) {
			$collumn = $entity->letterCollumn();

			$query = $entity->newQuery()->selectRaw("{$collumn}, count(id) as count");
			foreach ($filters as $filter)
				if ($filter != $this)
					$query = $filter->applyFilterToQuery($query);

			$fetched = $query
				->groupBy($collumn)
				->orderBy($collumn)
				->lists('count', $collumn);

			$result = [];
			foreach ($fetched as $letter => $count) {
				preg_match('/(.)/iu', $letter, $match);
				$letter = mb_strtoupper($match[1]);
				$result[$letter] = (isset($result[$letter]) ? $result[$letter] : 0) + $count;
			}

			return $result;
		}

	}
