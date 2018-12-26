<?php namespace Ankh\Feeds;

class PageFeedChanel extends UpdatesFeedChanel {

	public function name() {
		return 'page';
	}

	public function entityRepositoryClass() {
		return \Ankh\Contracts\PageRepository::class;
	}

	public function titleParam() {
		return $this->entity()->title;
	}

}

