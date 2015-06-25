<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Group;

	class Author extends Model {

		public function groups() {
			return $this->hasMany('Ankh\Group');
		}


	}
