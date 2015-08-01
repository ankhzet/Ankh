<?php

	namespace Ankh\Http\Controllers;

	class HomeController extends Controller {

		public function anyIndex() {
			return view('home');
		}

		public function getAdmin() {
			return view('admin.home');
		}

	}
