<?php

namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

use Ankh\Facades\Breadcrumbs as AnkhBreadcrumbsFacade;
use Ankh\Crumbs;

use Ankh\Facades\PageutilsFacade;
use Ankh\PageUtils;


class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['charset-encoder'] = $this->app->share(function ($app) {
			return new \Ankh\CharsetEncoder();
		});

		$this->app['crumbs'] = $this->app->share(function($app) {
			return new Crumbs($app['router'], $app['route']);
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Crumbs', AnkhBreadcrumbsFacade::class);
		});

		$this->registerPageUtilsFacade();
		$this->registerHtmlCleaner();

		$this->registerUrlFetcher();

		if ($this->app->environment() == 'local') {

			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');

			$this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');

		}
	}

	function registerPageUtilsFacade() {
		$this->app['pageutils'] = $this->app->share(function($app) {
			return new PageUtils($app[\Ankh\Contracts\HtmlCleaner::class]);
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('PageUtils', PageutilsFacade::class);
		});
	}

	function registerHtmlCleaner() {
		$this->app->bind(\Ankh\Contracts\HtmlCleaner::class, \Ankh\TidyCleaner::class);
	}

	function registerUrlFetcher() {
		$this->app->bind(\Ankh\Contracts\Synk\Fetcher::class, \Ankh\Synk\Fetching\SamlibFetcher::class);
		$this->app->bind(\Ankh\Contracts\Synk\Fetch::class, \Ankh\Synk\Fetching\CachedFetch::class);
	}

}


