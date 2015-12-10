<?php namespace Ankh\Feeds;

use Lang;
use Date;

use Ankh\PageUpdate;

class UpdatesFeedChanel extends FeedChanel {

	protected $entity;

	public function name() {
		return 'updates';
	}

	public function url() {
		$url = parent::url();
		if ($id = $this->id())
			$url .= '/' . $id;
		return $url;
	}

	public function of($identifier) {
		$instance = parent::of($identifier);

		if ($id = intval($identifier)) {
			$repository = app($this->entityRepositoryClass());
			$instance->entity = $repository->findEvenTrashed($id);
		}

		return $instance;
	}

	public function entityRepositoryClass() {
		return \Ankh\Contracts\EntityRepository::class;
	}

	public function entity() {
		return $this->entity;
	}

	public function feedItems(\Closure $consumer, $limit = 0) {
		$last = 0;

		$items = PageUpdate::diff()->orderBy('created_at', 'desc');
		if ($limit)
			$items = $items->take($limit);

		foreach ($items->get() as $item)
			if ($this->filter($item)) {
				$page = $item->relatedPage();
				$author = $item->relatedAuthor();

				$feed = new FeedItem();
				$feed->title = $page->title;
				$feed->author = $author->fio;
				$feed->created = $item->created_at;

				$feed->url = route('pages.updates.index', $page) . '?highlite=' . $item->version()->encode();

				$feed->description = view('rss.diff-item', ['update' => $item, 'feed' => $feed]);

				$consumer($feed);

				if ($feed->created->timestamp > $last)
					$last = $feed->created->timestamp;
			}

		return $last ? Date::createFromTimestamp($last) : Date::now();
	}

	public function filter(PageUpdate $update) {
		if (!$this->id())
			return true;

		$name = ucfirst($this->name());

		$entity = $update->{"related{$name}"}();

		return $entity && ($this->entity->id == $entity->id);
	}

}
