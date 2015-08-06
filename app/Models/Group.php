<?php

	namespace Ankh;

	use Ankh\Author;
	use Ankh\Page;

	class Group extends Entity {

		protected $guarded = ['id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public static function getList() {
			$result = [];
			foreach (static::all() as $group)
				$result[$group->id] = $group->title;

			return $result;
		}

	}
