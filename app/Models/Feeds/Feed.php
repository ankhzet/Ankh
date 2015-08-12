<?php namespace Ankh\Feeds;

use Config;
use Lang;

use Ankh\Contracts\Feeds\Feed as FeedContract;
use Ankh\Contracts\Feeds\FeedChanel as FeedChanelContract;

use Roumen\Feed\Facades\Feed as FeedEngine;

class Feed implements FeedContract {

	public function __construct(FeedChanel $chanel = null) {
	}

	public function make(FeedChanelContract $chanel) {
	}


	public function format() {
		return FeedConfig::get('format', 'atom');
	}

	public function render() {
	}

}

