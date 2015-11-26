<?php namespace Ankh\Jobs;

use Carbon\Carbon;
use Queue;
use Log;

use Ankh\Page\Comparator;
use Ankh\Version;
use Ankh\PageUpdate;

class CheckPage extends Job {

	protected $update;

	function __construct($update) {
		$this->update = $update;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle() {
		$update = PageUpdate::withTrashed()->find($this->update);

		$page = $update->relatedPage();
		$version = $page->version($update->created_at);

		Log::info("Checking page " . $page->id . "...");
		$compare = with(new Comparator)->compareLast($version);

		if ($compare === false)
			throw new \Exception("Page {$page->id} check failed");

		if ($compare->equals()) {
			Log::info("Page {$page->id} check found no differences");
		} else {
			$update->restore();
		}
	}

	public static function checkLater(PageUpdate $update) {
		$job = new static($update->id);
		Queue::pushOn('page-check', $job);
	}

}
