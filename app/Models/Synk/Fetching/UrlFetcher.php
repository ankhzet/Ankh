<?php namespace Ankh\Synk\Fetching;

use Exception;

use Ankh\Contracts\Synk\Fetch as FetchContract;
use Ankh\Contracts\Synk\Fetcher as FetcherContract;

class UrlFetcher implements FetcherContract {

	protected $curl;

	protected $userAgent = 'AnkhZet Cache Sync Bot v0.2';
	protected $server;
	protected $timeout = 30;

	protected $proxy;

	public function fetch(FetchContract $fetch) {
		$link = $fetch->resource();

		if ($link == '/')
			throw new Exception('Failed fetcher assertion: link should not be "/"');

		if (!$curl = $this->curl())
			throw new Exception('Curl initialization error');

		$url = $this->url($link);

		curl_setopt($curl, CURLOPT_URL, $url);


		try {

			curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use ($fetch) {

				if (!preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#i", $header)) {
					$parts = explode( ':', $header, 2);

					$headers = $fetch->data();
					if ((count($parts) > 1) && ($name = trim(array_shift($parts))) != '')
						$headers[$name] = trim(join(':', $parts));
					else
						if (($value = trim($header)) != '')
							$headers[] = $value;

					$fetch->data($headers);
				}

				return strlen($header);
			});

			$fetch->response(curl_exec($curl));
			$fetch->code(curl_getinfo($curl, CURLINFO_HTTP_CODE));

		} catch (Exception $e) {
			$fetch->code($e);
			return false;
		}

		return true;
	}

	public function isOk(FetchContract $fetch) {
		$response = $fetch->response();
		if (!isset($response))
			return false;

		if (!is_numeric($code = $fetch->code()))
			return false;

		switch ($code) {

		case 200:
		case 206:
			break;

		case 301:
		case 302:
			break;

		default :
			if (($code >= 400) && ($code < 600))
				return false;
		}

		return $response !== false;
	}

	function sourceServer() {
		return rtrim($this->server, '/');
	}

	function url($relative = '') {
		return rtrim($this->sourceServer(), '/') . '/' . ltrim($relative, '/');
	}

	function curl() {
		if (!$this->curl) {
			$this->curl = $c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($c, CURLOPT_HTTPHEADER, array('X-Bot' => $this->userAgent));
			curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);

			if ($this->proxy)
				$this->setupProxy();
		}

		return $this->curl;
	}

	protected function setupProxy($proxy = null) {
		$proxy = $proxy ?: $this->proxy;

		$context = explode(':', $this->server)[0];
		$r_default_context = stream_context_get_default(array(
			$context => array(
				'proxy' => $proxy,
				'request_fulluri' => true
				)
			));

		$p = explode(':', $proxy);
		$port = intval(array_pop($p));
		$address = $port ? join(':', $p) : $proxy;

		curl_setopt($this->curl, CURLOPT_PROXY, $address);
		if ($port)
			curl_setopt($this->curl, CURLOPT_PROXYPORT, $port);
	}

	protected function setupReferer($referer) {
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Referer' => $referer));
	}

}
