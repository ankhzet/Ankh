<?php namespace Ankh;

	class Author extends Updateable {

		protected $filterCollumn = 'fio';
		protected $guarded = ['id'];

		public function groups() {
			return $this->hasMany('Ankh\Group');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function absoluteLink() {
			return '/' . trim($this->link, '/');
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

			if (preg_match('"^https?://([^/]+)/(editors/)?(./[^/]+)"i', $link, $matches))
				if (array_search(strtolower($matches[1]), ['budclub.ru', 'samlib.ru']) !== false)
					return trim(trim($matches[3]), '/');

			return false;
		}

	}
