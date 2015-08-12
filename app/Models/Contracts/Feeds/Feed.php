<?php namespace Ankh\Contracts\Feeds;

interface Feed {

	public function make(FeedChanel $chanel);

	public function format();

	public function render();

}
