<?php namespace Ankh\Commands;

use Carbon\Carbon;
use Queue;

use Ankh\Page\Comparator;
use Ankh\Version;
use Ankh\PageUpdate;

class CheckPage extends Command {

	protected $update;

	function __construct(PageUpdate $update) {
		$this->update = $update;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle() {
		$page = $this->update->relatedPage();
		$version = $page->version($this->update->created_at);

		$compare = with(new Comparator)->compareLast($version);

		if ($compare === false)
			throw new \Exception("Page {$this->version->getEntity()->id} check failed");

		if ($compare->equals())
			$this->update->delete();
		else {

		}
	}

	public static function queue(PageUpdate $update) {
		$page = $update->relatedPage();
		$id = "page-{$page->id}-check";

		// clearing page-check queue
		while (Queue::pop($id) != null);

		Queue::pushOn($id, new static($update));
	}

}

