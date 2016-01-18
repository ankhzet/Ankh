<?php

namespace Ankh\Http\Middleware;

use Closure;

class Api {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		if ($request->isMethod('options'))
			return $this->addCORSHeaders(response('', 200));

		$request = $next($request);
		if (Subdomens::is('api'))
			$request = static::addCORSHeaders($request);

		return $request;
	}

	static function addCORSHeaders($to) {
		return $to
			// ->header('Access-Control-Max-Age', 0)
			->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
			->header('Access-Control-Allow-Headers', 'accept, content-type, x-requested-with, origin, x-xsrf-token, x-csrf-token')
			->header('Access-Control-Allow-Origin', '*');
	}

}
