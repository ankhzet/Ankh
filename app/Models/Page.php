<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Author;
	use Ankh\Group;

	class Page extends Model {

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function group() {
			return $this->belongsTo('Ankh\Group');
		}

	}
