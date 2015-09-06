<?php

namespace Ankh\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;

class Authenticate extends UserTest {

	public function test(Guard $auth) {
		return !$auth->guest();
	}

}
