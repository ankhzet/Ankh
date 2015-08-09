<?php namespace Ankh;

	use Ankh\Author;
	use Ankh\Group;
	use Ankh\PageResolver;

	class Page extends Updateable {

		protected $guarded = ['id'];
		protected $fillable = ['title', 'link', 'annotation', 'size', 'author_id', 'group_id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function group() {
			return $this->belongsTo('Ankh\Group');
		}

		public function resolver() {
			return with(new PageResolver)->setPage($this);
		}

		protected function updateType() {
			return PageUpdate::TYPE;
		}

		protected function updateClass() {
			return PageUpdate::class;
		}

	}
