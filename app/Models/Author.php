<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Group;
	use Ankh\Page;

	class Author extends Model {

		public function groups() {
			return $this->hasMany('Ankh\Group');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}


	}
