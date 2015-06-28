<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Author;
	use Ankh\Page;

	class Group extends Model {

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

	}
