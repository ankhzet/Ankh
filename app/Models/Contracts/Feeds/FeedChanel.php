<?php namespace Ankh\Contracts\Feeds;

interface FeedChanel {

	public function name();

	public function id();

	public function title();

	public function url();

	public function feedItems(\Closure $consumer);

}
