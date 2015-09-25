<?php namespace Ankh\Jobs;

use Illuminate\Contracts\Queue\ShouldBeQueued;

use Carbon\Carbon;
use Bus;

use Ankh\Page\Comparator;
use Ankh\Version;
use Ankh\PageUpdate;

class CheckPage extends Job implements ShouldBeQueued {

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

		$compare = with(new Comparator)->compareLast($version);

		if ($compare === false)
			throw new \Exception("Page {$page->id} check failed");

		if ($compare->equals()) {

		} else {
			$update->restore();
		}
	}

	public static function checkLater(PageUpdate $update) {
		$update->delete();
		$job = new static($update->id);
		$job->onQueue('page-check');
		$job->delay(60);
		Bus::dispatch($job);
	}

}

