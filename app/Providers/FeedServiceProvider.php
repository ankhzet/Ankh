<?php namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;

use Ankh\Facades\FeedFacade;
use Ankh\Feeds\Feed;

use Ankh\Facades\FeedChanelsFacade;
use Ankh\Feeds\FeedChanels;

use Ankh\Feeds\UpdatesFeedChanel;
use Ankh\Feeds\AuthorFeedChanel;
use Ankh\Feeds\GroupFeedChanel;
use Ankh\Feeds\PageFeedChanel;

class FeedServiceProvider extends ServiceProvider {

	public function register() {
		$this->app['feedcommonchanel'] = $this->app->share(function($app) {
			return new UpdatesFeedChanel();
		});

		$this->app['feedauthorchanel'] = $this->app->share(function($app) {
			return new AuthorFeedChanel();
		});

		$this->app['feedgroupchanel'] = $this->app->share(function($app) {
			return new GroupFeedChanel();
		});

		$this->app['feedpagechanel'] = $this->app->share(function($app) {
			return new PageFeedChanel();
		});

		$this->registerFacade('Feed', FeedFacade::class, $this->app->share(function($app) {
			return new Feed();
		}));

		$this->registerFacade('FeedChanels', FeedChanelsFacade::class, $this->app->share(function($app) {
			$c = new FeedChanels();
			$c->register($app['feedcommonchanel']);
			$c->register($app['feedauthorchanel']);
			$c->register($app['feedgroupchanel']);
			$c->register($app['feedpagechanel']);
			return $c;
		}));
	}

	public function registerFacade($alias, $class, $builder) {
		// first, resolve facade accessor
		$method = new \ReflectionMethod($class, 'getFacadeAccessor');
		$method->setAccessible(true);
		$resource = $method->invoke(null);

		// setup accessor
		$this->app[$resource] = $builder;

		// bind facade alias
		$this->app->booting(function() use ($alias, $class) {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias($alias, $class);
		});
	}

	public function boot() {
		$this->app->bind(\Ankh\Contracts\FeedChanels::class, \Ankh\Feeds\FeedChanels::class);
	}

}
