<?php

namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

use Ankh\Facades\Breadcrumbs as AnkhBreadcrumbsFacade;
use Ankh\Crumbs;

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

		$this->app['crumbs'] = $this->app->share(function($app) {
			return new Crumbs($app['router'], $app['route']);
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Crumbs', AnkhBreadcrumbsFacade::class);
		});

		if ($this->app->environment() == 'local') {

			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');

			$this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');

		}
	}
}


