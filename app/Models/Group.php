<?php

	namespace Ankh;

	use Illuminate\Database\Eloquent\Model;

	use Ankh\Contracts\EntityContract;

	use Ankh\Author;
	use Ankh\Page;

	class Group extends Model implements EntityContract {
		use \Ankh\Traits\Entity\DateAccessorTrait;

		use Traits\Entity\LayeredRepositoryTrait;

		use Traits\Entity\FilterableTrait;
		use Traits\Entity\OrderableTrait;

		use Traits\Entity\CollumnLetterTrait;

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
