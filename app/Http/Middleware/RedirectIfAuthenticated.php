<?php

namespace Ankh\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated extends UserTest {

	public function test(Guard $auth) {
		return !$auth->check();
	}

	public function redirect(Guard $auth, $request) {
		return redirect(route('home'));
	}

}
