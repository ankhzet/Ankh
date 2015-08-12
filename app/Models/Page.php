<?php namespace Ankh;

	class Page extends Updateable {

		protected $guarded = ['id'];
		protected $fillable = ['title', 'link', 'annotation', 'size', 'author_id', 'group_id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function group() {
			return $this->belongsTo('Ankh\Group');
		}

		public function absoluteLink() {
			return $this->author->absoluteLink() . '/' . trim($this->link, '/');
		}

		public function resolver() {
			return with(new PageResolver)->setPage($this);
		}

		public function updateType() {
			return PageUpdate::TYPE;
		}

		public function updateClass() {
			return PageUpdate::class;
		}

	}
