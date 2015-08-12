<?php namespace Ankh\Feeds;

use Config;

class FeedConfig {

	public static function __callStatic($method, $args) {
		if (($method == 'get') || ($method == 'set')) {
			$args[0] = "feed.{$args[0]}";
		}

		switch (count($args)) {
		case 0:
			return forward_static_call(['Config', $method]);
		case 1:
			return forward_static_call(['Config', $method], $args[0]);
		case 2:
			return forward_static_call(['Config', $method], $args[0], $args[1]);
		case 3:
			return forward_static_call(['Config', $method], $args[0], $args[1], $args[2]);
		case 4:
			return forward_static_call(['Config', $method], $args[0], $args[1], $args[2], $args[4]);
		default:
			throw new Exception("To much parameters");
		}
	}

}
