<?php namespace Ankh\Http\Controllers\Admin;

use Ankh\Http\Controllers\Controller;

use Ankh\Admin\LogFile;
use Ankh\Admin\LogParser;
use Ankh\Downloadable\DownloadWorker;

use Ankh\Page;
use Ankh\PageUpdate;
use Ankh\Update;

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

	public function cleanupPages() {
		return $this->anyCleanup((new Cleaner([
			'deleted-pages' => new CleanerDBDeletedPages,
		]))->cleanup());
	}

	public function cleanupUpdates() {
		return $this->anyCleanup((new Cleaner([
			'outdated-updates' => new CleanerDBOutdatedUpdates,
		]))->cleanup());
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


class CleanerDBDeletedPages {

	public function clean() {
		$pages = Page::onlyTrashed();
		$pageIds = $pages->get(['id'])->pluck('id', 'id');

		$trough = PageUpdate::withTrashed()->get(['id', 'entity_id'])->all();

		$same = array_filter(array_map(function ($e) use ($pageIds) {
			return isset($pageIds[$e->entity_id]) ? $e->id : false;
		}, $trough));

		$updates = Update::withTrashed()->whereIn('id', $same);
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

class CleanerDBOutdatedUpdates {

	public function clean() {
		$old = Update::withTrashed();
		$old = $old->orderBy('id')->take($old->count() / 2);

		$deleted = Update::onlyTrashed();
		$updateIds = array_unique(
			array_merge(
				$deleted->get(['id'])->pluck('id')->toArray(),
				$old->get(['id'])->pluck('id')->toArray()
			)
		);

		$updates = Update::withTrashed()->whereIn('id', $updateIds);
		$updates->forceDelete();

		$this->stats = [
			'updates' => $updateIds,
		];
		return $this->stats;
	}

	public function __toString() {
		return json_encode($this->stats);
	}

}
