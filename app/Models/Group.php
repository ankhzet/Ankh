<?php namespace Ankh;

	class Group extends Updateable {

		protected $guarded = ['id'];
		protected $fillable = ['title', 'link', 'annotation', 'inline', 'author_id'];

		public function author() {
			return $this->belongsTo('Ankh\Author')->withTrashed();
		}

		public function pages() {
			return $this->hasMany('Ankh\Page');
		}

		public function absoluteLink() {
			if (!$this->link)
				return null;

			return path_join($this->author->absoluteLink(), $this->link);
		}

		public function peekPages(&$delta, $amount = 10, $paginate = false) {
			if ($paginate)
				return $this->pages()->orderBy('title')->paginate($amount);

			$paginator = $this->pages()->take($amount);
			$delta = $paginator->count() - $amount;
			return $paginator->orderBy('updated_at', 'desc');
		}

		public function updateType() {
			return GroupUpdate::TYPE;
		}

		public function updateClass() {
			return GroupUpdate::class;
		}

		public function infoUpdateCapture(array $over = []) {
			return array_merge_recursive(
				parent::infoUpdateCapture(),
				[
					'-annotation' => Update::U_INFO,
				]
			);
		}

		public function __toString() {
			return \HTML::link(route('groups.show', $this), $this->title, ['target' => '_blank']);
		}

	}
