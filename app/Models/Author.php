<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Contracts\EntityContract;

	use Ankh\Group;
	use Ankh\Page;

	class Author extends Model implements EntityContract {

		use \Ankh\Traits\Entity\LayeredRepositoryTrait;

		use \Ankh\Traits\Entity\FilterableTrait;
		use \Ankh\Traits\Entity\OrderableTrait;

		use \Ankh\Traits\Entity\CollumnLetterTrait;

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

		public static function getList() {
			$result = [];
			foreach (static::all() as $author)
				$result[$author->id] = $author->fio;

			return $result;
		}

	}
