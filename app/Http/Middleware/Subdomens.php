<?php

namespace Ankh\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class Subdomens {

	const SUBDOMEN_COOKIE = 'subdomen';

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$host = preg_replace('"^\w+://(www\.)?"i', '', \Config::get('app.url'));
		$test = str_replace($host, '', $request->getHttpHost());
		preg_match("'^([^\.]+)\.$'", $test, $m);

		static::subdomen(@$m[1]);

		return $next($request);
	}

	public static function subdomen($value = null) {
		$request = app('request');
		if ($value !== null)
			$request->cookies->set(static::SUBDOMEN_COOKIE, $value);

		return $request->cookie(static::SUBDOMEN_COOKIE);
	}

	public static function is($subdomen) {
		return ($s = static::subdomen()) ? Str::is($subdomen, $s) : null;
	}
}
