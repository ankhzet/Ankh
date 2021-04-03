<?php namespace Ankh\Feeds;

use Config;
use Lang;

use Ankh\Contracts\Feeds\Feed as FeedContract;
use Ankh\Contracts\Feeds\FeedChanel as FeedChanelContract;

class Feed implements FeedContract {

	const DEF_LIMIT = 30;

	protected $feeder;
	protected $limit = self::DEF_LIMIT;

	public function __construct(FeedChanel $channel = null) {
		$this->feeder = \App::make('FeedEngine');
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

	public function make(FeedChanelContract $channel) {
		$this->feeder->setCache(10, $channel->url());

	    if (false && $this->feeder->isCached()) {
	        return $this;
        }

		$this->feeder->title = Lang::get('common.site') . ' - ' . preg_replace('"<"', '&lt;', html_entity_decode($channel->title()));

		$this->feeder->link = $channel->url();

		$this->feeder->pubdate = $channel->feedItems(function ($item) {
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

