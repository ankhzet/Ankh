<?php namespace Ankh\Synk\Fetching;

use Cache;
use Exception;

class Fetcher {

	var $server = 'http://samlib.ru';
	var $timeout = 60;
	var $userAgent = 'AnkhZet Cache Sync Bot v0.2';

	protected $cache;
	protected $cached = true;

	protected $curl;

	protected $minutes = 60 * 1;

	public function __construct() {
		$this->cache = Cache::store('file');
	}

	public function pull($link, &$params = []) {
		if ($link == '/')
			throw new Exception('Failed fetcher assertion: link should not be "/"');

		$url = $this->url($link);

		$cached = isset($params['cached']) ? $params['cached'] : $this->cached;

		return$this->pullFromCache($url, $params, !$cached);
	}

	public function sourceServer() {
		return trim($this->server, '/');
	}

	public function url($relative = '') {
		return rtrim($this->sourceServer(), '/') . '/' . ltrim($relative, '/');
	}

	public function pullFromCache($link, &$params = [], $flush = false) {
		$key = $this->key($link);

		if ($flush)
			$this->flush($link);
		else
			if ($this->cache->has($key)) {
				$params['code'] = 200;
				$params[200] = true;
				$params['speed'] = 0;
				$params['time'] = 0;
			}

		$data = $this->cache->remember($key, $this->minutes, function () use ($link, &$params) {
			return $this->pullFromServer($link, $params);
		});

		$params['length'] = strlen($data);
		return $data;
	}

	public function flush($link) {
		$this->cache->forget($this->key($link));
	}

	function key($link) {
		return md5($link);
	}

	public function pullFromServer($link, &$params = []) {
		// dump("fetching [$link]...");

		if (!$curl = $this->curl())
			throw new Exception('Curl initialization error');

		curl_setopt($curl, CURLOPT_URL, $link);
		if (isset($params['referer']))
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Referer' => $params['referer']));

		$start = microtime(true);
		try {
			$response = curl_exec($curl);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			$params['code'] = $code;
			$params[$code] = true;
			switch ($code) {
			case 200:
			case 206:
			case 301:
			case 302:
				break;
			default :
				if (($code >= 400) && ($code < 600))
					$response = false;
			}
		} catch (Exception $e) {
			$params['exception'] = (string)$e;
			return false;
		}

		$time = microtime(true) - $start;

		$params['speed'] = strlen($response) / $time;
		$params['time'] = $time;

		return $response ? $response : false;
	}


	function curl() {
		if (!$this->curl) {
			$this->curl = $c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($c, CURLOPT_HTTPHEADER, array('X-Bot' => $this->userAgent));
			curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
		}

		return $this->curl;
	}

}
