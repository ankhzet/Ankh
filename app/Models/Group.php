<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Author;

	class Group extends Model {

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

	}
