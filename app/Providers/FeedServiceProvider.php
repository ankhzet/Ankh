<?php namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;

use Ankh\Facades\FeedFacade;
use Ankh\Feeds\Feed;

use Ankh\Facades\FeedChanelsFacade;
use Ankh\Feeds\FeedChanels;

class FeedServiceProvider extends ServiceProvider {

	public function register() {
		$this->registerFacade('Feed', FeedFacade::class, $this->app->share(function($app) {
			return new Feed();
		}));

		$this->registerFacade('FeedChanels', FeedChanelsFacade::class, $this->app->share(function($app) {
			$c = new FeedChanels();
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
		$this->app->booting(function() use($alias, $class) {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias($alias, $class);
		});
	}

	public function boot() {
		$this->app->bind(\Ankh\Contracts\FeedChanels::class, \Ankh\Feeds\FeedChanels::class);
	}

}

