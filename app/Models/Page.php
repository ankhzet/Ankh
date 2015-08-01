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

		protected $guarded = ['id'];
		protected $fillable = ['title', 'link', 'annotation', 'size', 'author_id', 'group_id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function group() {
			return $this->belongsTo('Ankh\Group');
		}

		public static function getList($instance = null, $sub = null) {
			$result = [];
			if ($sub && $sub != get_class()) {
				switch ($sub) {
					case Group::class:
						foreach ($instance->author->groups as $group)
							$result[$group->id] = $group->title;
						break;

					default:
						throw new \Exception("Don't know how to getList()'s entities of class '$sub'");
				}
			} else
				foreach (static::all() as $page)
					$result[$page->id] = $page->title;

			return $result;
		}


	}
