<?php namespace Ankh\Feeds;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

