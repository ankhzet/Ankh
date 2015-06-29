<?php

	namespace Ankh\Entity\Filters;

	use Ankh\Contracts\EntityContract as Entity;
	use Ankh\Contracts\FilterContract;

	class LetterFilter extends BasicFilter {
		protected $letter = null;

		public function __construct($letter) {
			$this->letter($letter);
		}

		public function applyFilterToQuery($query) {
			$applied = \DB::raw("({$query->toSql()}) as a");
			$applied = \DB::table($applied)->mergeBindings($query->getQuery());

			return $applied->where('letter', '=', $this->letter());
		}

		public function letter($value = null) {
			if ($value !== null)
				$this->letter = mb_strtoupper($value);

			return $this->letter;
		}

		public function lettersUsage(Entity $entity, array $filters = []) {
			$query = $entity->newQuery()->selectRaw("{$entity->letterCollumn()}, count(`id`) as `count`");
			foreach ($filters as $filter)
				$query = $filter->applyFilterToQuery($query);

			return $query
				->groupBy('letter')
				->orderBy('letter')
				->lists('count', 'letter');
		}

	}
