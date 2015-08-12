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

	}
