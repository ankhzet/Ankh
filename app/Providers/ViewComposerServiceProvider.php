<?php namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;

use Ankh\Update;
use Ankh\PageUpdate;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function boot() {
		$mapping = [
			Update::U_ADDED => 'green',
			Update::U_DELETED => 'red',
			Update::U_RENAMED => 'teal',
			Update::U_INFO => 'olive',
			PageUpdate::U_MOVED => 'blue',
			PageUpdate::U_DIFF => false,
		];

		$this->app['view']->composer('updates.item', function($view) use ($mapping) {
			$update = $view['update'];
			$tag = ($mapped = @$mapping[$update->type]) ? "<b class=\"delta $mapped\">{$update}</b>" : (string)$update;
			$view['update_tag'] = $tag;
		});

	}

	public function register() {
	}

}
