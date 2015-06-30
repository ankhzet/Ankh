<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Contracts\EntityContract;

	use Ankh\Author;
	use Ankh\Group;

	class Page extends Model implements EntityContract {

		use Traits\Entity\LayeredRepositoryTrait;

		use Traits\Entity\FilterableTrait;
		use Traits\Entity\OrderableTrait;

		use Traits\Entity\CollumnLetterTrait;

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function group() {
			return $this->belongsTo('Ankh\Group');
		}

	}
