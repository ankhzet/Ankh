<?php namespace Ankh\Feeds;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupFeedChanel extends UpdatesFeedChanel {

	public function name() {
		return 'group';
	}

	public function entityRepositoryClass() {
		return \Ankh\Contracts\GroupRepository::class;
	}

	public function titleParam() {
		return $this->entity()->title;
	}

}

