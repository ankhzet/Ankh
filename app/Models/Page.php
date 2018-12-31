<?php namespace Ankh;

	use Closure;

	use Ankh\Contracts\Resolvable;
	use Ankh\Jobs\CheckPage as CheckJob;

	/**
	 * @property mixed title
	 * @property mixed link
	 * @property mixed annotation
	 * @property mixed size
	 * @property mixed author_id
	 * @property mixed group_id
	 * @property mixed author
	 * @property mixed group
	 */
	class Page extends Updateable implements Resolvable {

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
			return path_join($this->author->absoluteLink(), $this->link);
		}

		public function resolver() {
			return (new PageResolver)->setPage($this);
		}

		/**
		 * @param null $timestamp
		 * @return Version
		 */
		public function version($timestamp = null) {
			return (new Version($timestamp))->setEntity($this);
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

		public function infoUpdateCapture() {
			return array_merge_recursive(
				parent::infoUpdateCapture(),
				[
					'group_id' => PageUpdate::U_MOVED,
					'-annotation' => Update::U_INFO,
					'size' => PageUpdate::U_DIFF,
				]
			);
		}

		public function newUpdate($type, Closure $callback = null) {
			$update = parent::newUpdate($type, $callback);

			switch ($type) {
			case PageUpdate::U_ADDED:
			case PageUpdate::U_DIFF:
				CheckJob::checkLater($update);
			}

			return $update;
		}

	}
