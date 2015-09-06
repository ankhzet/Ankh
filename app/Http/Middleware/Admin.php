<?php

namespace Ankh\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;

class Admin extends Authenticate {

	public function test(Guard $auth) {
		if (!$auth->check())
			return false;

		return $auth->user()->isAdmin();
	}

}
