<?php namespace Ankh\Synk\Fetching;

use Ankh\Contracts\Synk\Fetch as FetchContract;

class Fetch implements FetchContract {

	protected $fetcher;

	var $code;
	var $resource;
	var $response;
	var $data = [];

	var $time;

	public function __construct(\Ankh\Contracts\Synk\Fetcher $fetcher) {
		$this->fetcher = $fetcher;
	}

	public function pull($resource) {
		$this->code = 200;
		$this->response = null;
		$this->resource = $resource;

		$start = microtime(true);

		$pulled = $this->fetcher->fetch($this);

		$this->time = microtime(true) - $start;

		return ($pulled && $this->isOk()) ? $this->response() : false;
	}

	public function isOk() {
		return $this->fetcher->isOk($this);
	}

	public function resource($resource = null) {
		if (isset($resource))
			$this->resource = $resource;

		return $this->resource;
	}

	public function code($code = null) {
		if (isset($code))
			$this->code = $code;

		return $this->code;
	}

	public function response($response = null) {
		if (isset($response))
			$this->response = $response;

		return $this->response;
	}

	public function data($data = null) {
		if (isset($data))
			$this->data = $data;

		return $this->data;
	}

	public function time() {
		return $this->time;
	}

}

