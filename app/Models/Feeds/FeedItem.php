<?php namespace Ankh\Feeds;

class FeedItem {

	private $data = [];

	function __construct() {
	}

	public function __get($property) {
		return isset($this->data[$property]) ? $this->data[$property] : null;
	}

	public function __set($property, $value) {
		$this->data[$property] = $value;
	}

}
