<?php namespace Ankh\Jobs;

use Queue;
use Log;

use Ankh\Page\Comparator;
use Ankh\PageUpdate;

class CheckPage extends Job {

	/**
	 * @var string
	 */
	protected $update;

	function __construct(string $update) {
		$this->update = $update;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle() {
		/** @var PageUpdate $update */
		$update = PageUpdate::withTrashed()->find($this->update);

		$page = $update->relatedPage();
		$version = $page->version($update->created_at);

		Log::info("Checking page " . $page->id . "...");
		$compare = (new Comparator)->compareLast($version);

		if ($compare === false) {
			throw new \Exception("Page {$page->id} check failed");
		}

		if ($compare->equals()) {
			Log::info("Page {$page->id} check found no differences");
		} else {
			$update->restore();
		}
	}

	public static function checkLater(PageUpdate $update) {
		Queue::pushOn('page-check', new static($update->id));
	}

}
