<?php namespace Ankh\Http\Controllers\Admin;

use Ankh\Http\Controllers\Controller;

use Ankh\Admin\LogFile;
use Ankh\Admin\LogParser;
use Ankh\Downloadable\DownloadWorker;

use Ankh\Page;
use Ankh\PageUpdate;

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

	public function anyCleanup($statistics = null) {
		return view('admin.cleanup', compact('statistics'));
	}

	public function cleanupDb() {
		return $this->anyCleanup((new Cleaner(['db' => new CleanerDB]))->cleanup());
	}

}

class Cleaner {

	function __construct($cleaners = []) {
		$this->cleaners = $cleaners;
	}

	public function cleanup() {
		$stats = [];
		foreach ($this->cleaners as $name => $cleaner) {
			$stats[$name] = $cleaner->clean();
		}
		return $stats;
	}

}


class CleanerDB {

	public function clean() {
		$pages = Page::onlyTrashed();
		$pageIds = $pages->get(['id'])->pluck('id');

		$updates = PageUpdate::withTrashed()->whereIn('entity_id', $pageIds);
		$updateIds = $updates->get(['id'])->pluck('id');

		$updates->forceDelete();
		$pages->forceDelete();

		$this->stats = [
			'pages' => $pageIds->toArray(),
			'updates' => $updateIds->toArray(),
		];
		return $this->stats;
	}

	public function __toString() {
		return json_encode($this->stats);
	}

}
