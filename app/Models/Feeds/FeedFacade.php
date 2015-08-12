<?php namespace Ankh\Facades;

use Illuminate\Support\Facades\Facade;

class FeedFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'ankh-feed'; }

}
