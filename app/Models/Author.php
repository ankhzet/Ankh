<?php namespace Ankh;

	class Author extends Updateable {

		const RENAME_FIELD = 'fio';

		protected $filterCollumn = 'fio';
		protected $guarded = ['id'];

		public function groups() {
			return $this->hasMany('Ankh\Group');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function peekGroups(&$delta, $amount = 10, $paginate = false) {
			if ($paginate)
				return $this->groups()->paginate($amount);

			$paginator = $this->groups()->take($amount);
			$delta = $paginator->count() - $amount;
			return $paginator->orderBy('updated_at', 'desc');
		}

		public function absoluteLink() {
			return path_join('/', $this->link, '/');
		}

		public function collumn($value = null) {
			if ($value !== null)
				$this->filterCollumn = $value;

			return $this->filterCollumn;
		}

		public function updateType() {
			return AuthorUpdate::TYPE;
		}

		public function updateClass() {
			return AuthorUpdate::class;
		}

		public static function matchLink($link) {
			$link = trim(trim(str_replace('\\', '/', $link)), '/');

			if (preg_match('"^./[^/]+$"i', $link, $matches))
				return $link;

			if (preg_match('"^https?://(?<host>[^/]+)/((editors|comment)/)?(?<link>./[^/]+)"i', $link, $matches))
				if (array_search(strtolower($matches['host']), ['budclub.ru', 'samlib.ru']) !== false)
					return trim(trim($matches['link']), '/');

			return false;
		}

		public function infoUpdateCapture(array $over = []) {
			return array_merge_recursive(
				parent::infoUpdateCapture(),
				[
					'-*rating' => Update::U_INFO,
					'-*visitors' => Update::U_INFO,
					'link' => Update::U_INFO
				]
			);
		}

	}
