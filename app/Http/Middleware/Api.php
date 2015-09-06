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
		if (!Subdomens::is('api')) {
			return response('API-only method', 404);
		}

		return $next($request);
	}

}
