<?php namespace Ankh\Feeds;

use Config;
use Lang;

use Ankh\Contracts\Feeds\Feed as FeedContract;
use Ankh\Contracts\Feeds\FeedChanel as FeedChanelContract;

use Roumen\Feed\Facades\Feed as FeedEngine;

class Feed implements FeedContract {

	const DEF_LIMIT = 30;

	protected $feeder;
	protected $limit = self::DEF_LIMIT;

	public function __construct(FeedChanel $chanel = null) {
		$this->feeder = FeedEngine::make();
		$this->feeder->lang = Config::get('app.locale', 'en');
		$this->feeder->logo = asset('assets/img/logo.png');
		$this->feeder->setDateFormat('carbon');
		$this->feeder->setShortening(false);
		$this->feeder->description = Lang::get('pages.rss.description');
	}

	public function limit($limit) {
		$this->limit = $limit ?: self::DEF_LIMIT;
		return $this;
	}

	public function make(FeedChanelContract $chanel) {
		$this->feeder->title = Lang::get('common.site') . ' - ' . $chanel->title();

		$this->feeder->link = $chanel->url();

		$this->feeder->pubdate = $chanel->feedItems(function ($item) {
			$this->feeder->add(
				$item->title,
				$item->author,
				$item->url,
				$item->created,
				$item->description,
				$item->content
			);
		}, $this->limit);

		return $this;
	}


	public function format() {
		return FeedConfig::get('format', 'atom');
	}

	public function render() {
		$this->feeder->setView('rss.' . $this->format());
		return $this->feeder->render($this->format());
	}

}

