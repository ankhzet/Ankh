<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rush\Facades\Renderer as RendererFacade;
use Rush\Renderer\Renderer as Renderer;

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

		$this->app['rushrenderer'] = $this->app->share(function($app) {
			return new Renderer;
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Renderer', RendererFacade::class);
		});

		if ($this->app->environment() == 'local') {
			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
		}
	}
}


