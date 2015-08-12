<?php namespace Ankh\Feeds;

use Lang;
use URL;

use Ankh\Contracts\Feeds\FeedChanel as FeedChanelContract;

abstract class FeedChanel implements FeedChanelContract {

	protected $id;

	public function __construct($id = null) {
		$this->id = $id;
	}

	public function of($identifier) {
		return new static($identifier);
	}

	public function id() {
		return $this->id;
	}

	public function title() {
		return Lang::get('pages.rss.' . $this->name(), ['param' => $this->titleParam()]);
	}

	public function url() {
		return URL::to('rss/' . $this->name());
	}

	public function titleParam() {
		return null;
	}

}
