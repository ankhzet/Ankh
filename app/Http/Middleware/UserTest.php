<?php

namespace Ankh\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserTest {
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (!$this->test($this->auth))
			return $this->redirect($this->auth, $request);

		return $next($request);
	}

	/**
	 * Test auth for acceptance.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function test(Guard $auth) {
		return true;
	}

	/**
	 * Redirect if test not passed.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function redirect(Guard $auth, $request) {
		if ($request->ajax()) {
			return response('Unauthorized.', 401);
		} else {
			return redirect()->guest(route('login'));
		}
	}

}
