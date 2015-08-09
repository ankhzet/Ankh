<?php namespace Ankh;

	use Ankh\Group;
	use Ankh\Page;

	class Author extends Updateable {

		protected $filterCollumn = 'fio';
		protected $guarded = ['id'];

		public function groups() {
			return $this->hasMany('Ankh\Group');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function collumn($value = null) {
			if ($value !== null)
				$this->filterCollumn = $value;

			return $this->filterCollumn;
		}

		protected function updateType() {
			return AuthorUpdate::TYPE;
		}

		protected function updateClass() {
			return AuthorUpdate::class;
		}

	}
