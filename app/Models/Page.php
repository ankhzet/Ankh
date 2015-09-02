<?php namespace Ankh;

	use Closure;

	class Page extends Updateable {

		const COLUMN_SIZE = 'size';

		protected $guarded = ['id'];
		protected $fillable = ['title', 'link', 'annotation', 'size', 'author_id', 'group_id'];

		public function author() {
			return $this->belongsTo('Ankh\Author')->withTrashed();
		}

		public function group() {
			return $this->belongsTo('Ankh\Group')->withTrashed();
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

		protected function wasCreated(Closure $callback = null) {
			parent::wasCreated(function ($update) {
				$update->change = $this->pickAttr(static::COLUMN_SIZE, $this->attributes);
			});
		}

		protected function willBeDeleted(Closure $callback = null) {
			parent::willBeDeleted(function ($update) {
				$update->change = $this->pickAttr(static::COLUMN_SIZE, []);
			});
		}

		protected function infoUpdateCapture() {
			return array_merge_recursive(
				parent::infoUpdateCapture(),
				[
					'group_id' => PageUpdate::U_MOVED,
					'-annotation' => Update::U_INFO,
					'size' => PageUpdate::U_DIFF,
				]
			);
		}

	}
