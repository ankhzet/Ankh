<?php namespace Ankh\Synk\Fetching;

use Cache;

class CachedFetch extends Fetch {

	protected $cache;
	protected $cached = true;
	protected $minutes = 60;

	protected $fromCache = false;

	public function __construct(\Ankh\Contracts\Synk\Fetcher $fetcher) {
		parent::__construct($fetcher);

		$this->cache = Cache::store('file');
		if ($this->cached = config('synk.cache.enabled', $this->cached)) {
			$this->minutes = config('synk.cache.ttl', $this->minutes);
		}
	}

	public function cached($cached = true) {
		$this->cached = $cached;
		return $this;
	}

	public function pull($link) {
		$this->resource($link);

		if ($this->cached) {
			if ($data = $this->pullFromCache())
				return $data;
		}

		$data = $this->pullFromServer();

		if ($this->cached && $data)
			$this->cache($data);

		return $data;
	}

	public function isOk() {
		if ($this->fromCache)
			return isset($this->data);

		return parent::isOk();
	}

	public function cache($data) {
		$this->cache->put($this->key(), $data, $this->minutes);
	}

	public function flush() {
		$this->cache->forget($this->key());
	}

	public function pulledFromCache() {
		return $this->fromCache;
	}

	function pullFromServer() {
		$this->fromCache = false;
		return parent::pull($this->resource());
	}

	function pullFromCache() {
		$this->fromCache = true;
		return $this->response($this->cache->remember($this->key(), $this->minutes, function () {
			return $this->pullFromServer();
		}));
	}

	function key() {
		return md5($this->resource());
	}

}
