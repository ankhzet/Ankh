<?php namespace Ankh;

	use Ankh\Author;
	use Ankh\Page;

	class Group extends Updateable {

		protected $guarded = ['id'];

		public function author() {
			return $this->belongsTo('Ankh\Author');
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function peekPages($amount, &$delta) {
			$paginator = $this->pages()->take($amount)->orderBy('updated_at', 'desc');
			$delta = $paginator->count() - $amount;
			return $paginator->get();
		}

		protected function updateType() {
			return GroupUpdate::TYPE;
		}

		protected function updateClass() {
			return GroupUpdate::class;
		}

	}
