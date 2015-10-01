<?php namespace Ankh\Http\Controllers\Admin;

use Ankh\Http\Controllers\Controller;

use Ankh\Admin\LogFile;
use Ankh\Admin\LogParser;
use Ankh\Downloadable\DownloadWorker;

class AdminController extends Controller {

	public function anyIndex() {
		return view('home');
	}

	public function getLog() {
		$log = new LogFile('laravel.log');

		$log = with(new LogParser)->parse($log->data());
		return view('admin.log', ['log' => $log]);
	}

	public function getDownloadLog() {
		$log = new LogFile('laravel.log');

		$log = app('ziper')->apply($log);

		return new DownloadWorker($log);
	}

	public function deleteDeleteLog() {
		$log = new LogFile('laravel.log');

		if (!$log->delete())
			throw new \Exception('Deletion failed!');

		return redirect()->back();
	}

}
