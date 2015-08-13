<?php namespace Ankh;

	class Group extends Updateable {

		protected $guarded = ['id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function absoluteLink() {
			return $this->link ? $this->author->absoluteLink() . '/' . trim($this->link, '/') : null;
		}

		public function peekPages($amount, &$delta) {
			$paginator = $this->pages()->take($amount);
			$delta = $paginator->count() - $amount;
			return $paginator->orderBy('updated_at', 'desc')->get();
		}

		public function updateType() {
			return GroupUpdate::TYPE;
		}

		public function updateClass() {
			return GroupUpdate::class;
		}

	}
