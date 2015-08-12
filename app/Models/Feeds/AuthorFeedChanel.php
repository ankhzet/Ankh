<?php namespace Ankh\Feeds;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorFeedChanel extends UpdatesFeedChanel {

	public function name() {
		return 'author';
	}

	public function entityRepositoryClass() {
		return \Ankh\Contracts\AuthorRepository::class;
	}

	public function titleParam() {
		return $this->entity()->fio;
	}

}

