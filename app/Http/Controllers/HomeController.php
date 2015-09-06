<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FeedChanels;
use Feed;

class HomeController extends Controller {

	public function anyIndex() {
		return view('home');
	}

	public function getAdmin() {
		return view('admin.home');
	}

	public function getTermsOfUse() {
	}

	public function getRSS(Request $request) {
		$chanel = FeedChanels::resolve($request);

		if (!$chanel)
			throw new NotFoundHttpException("RSS chanel not found");

		return Feed::make($chanel)->render();
	}

}
