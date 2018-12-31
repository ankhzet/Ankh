<?php namespace Ankh\Http\Controllers;

use Ankh\PageUpdate;
use Ankh\Update;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ankh\Http\Requests\PageRequest;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\PageRepository;
use Ankh\Crumbs as Breadcrumbs;

use Ankh\Version;
use Ankh\Downloadable\Transforms;
use Ankh\Downloadable\DownloadWorker;

use Ankh\Page\Diff;
use PageUtils;
use Log;

class PagesController extends RestfulController {
	protected $m;

	protected $filters = ['letter', 'author', 'group', 'offset'];
	protected $filtersMapping = ['author' => 'authors', 'group' => 'groups'];

	public function __construct(PageRepository $pages, Breadcrumbs $breadcrumbs) {
		$this->m = $pages;
	}

	protected function repository() {
		return $this->m;
	}

	/**
	 * Display a listing of the page entities.
	 *
	 * @return Response
	 */
	public function index() {
		$pages = parent::index();

		if (self::isApiCall())
			return response()->json(remap_for_json($pages->all()));

		$exclude = $this->hasFilters($this->filters);

		return $this->viewIndex(compact('pages', 'exclude'));
	}

	/**
	 * Display the specified page entity.
	 *
	 * @return Response
	 */
	public function show() {
		/** @var Author $author */
		/** @var Group $group */
		/** @var Page $page */
		list($author, $group, $page) = pick_arg(Author::class, Group::class, Page::class);
		$exclude = view_excludes(['author' => $author, 'group' => $group]);

		$updates = PageUpdate::where('entity_id', $page->id)->diff()->orderBy('created_at', 'desc')->get()->all();

		$groupper = new UpdatesGroupper($page, $updates);

		$versions = $groupper->flow();

		return $this->viewVersions(compact('page', 'updates', 'versions', 'exclude'));
	}

	/**
	 * Display the specified page entity.
	 *
	 * @return Response
	 */
	public function getRead() {
		/** @var Page $page */
		list($author, $group, $page, $version) = pick_arg(Author::class, Group::class, Page::class, Version::class);
		$exclude = view_excludes(['author' => $author, 'group' => $group]);

		$text = ($version ?: $page->version())->contents();

		if ($text === null) {
			throw new \Exception("Version {$version} not found");
		}

		return $this->viewShow(compact('page', 'text', 'exclude'));
	}

	/**
	 * Display the specified page versions diff.
	 *
	 * @param Page $page
	 * @param Version $v1
	 * @param Version $v2
	 * @return Response
	 */
	public function getDiff(Page $page, Version $v1, Version $v2) {
		$t1 = $v1->contents();
		$t2 = $v2->contents();

		if ($t1 === null)
			throw new \Exception("Version {$v1} not found");

		if ($t2 === null)
			throw new \Exception("Version {$v2} not found");

		$text = (new Diff)->diff($t1, $t2);

		return $this->viewShow(compact('page', 'text'));
	}

	/**
	 * Show the form for editing the specified page entity.
	 *
	 * @param Page $page
	 * @return Response
	 */
	public function edit($page) {
		$page = pick_arg(Page::class) ?: new Page;

		if (!$page->group) {
			$group = pick_arg(Group::class);

			if (!$group)
				throw new \Exception('Page can be created only if group is specified');

			$page->group = $group;
			$page->author = $group->author;
		}

		return $this->viewEdit(compact('page'));
	}

	/**
	 * Update the specified page entity in storage.
	 *
	 * @param  PageRequest $request
	 * @return Response
	 */
	public function update(PageRequest $request) {
		return $this->_update($request, pick_arg(Page::class));
	}

	public function getCheck(Page $page) {
		$updates = PageUpdate::where('entity_id', $page->id)->diff()->orderBy('created_at', 'desc')->get()->all();
		$ids = [];

		/** @var PageUpdate $update */
		foreach ($updates as $update) {
			if (!$update->pageVersion()->exists()) {
				$ids[] = $update->id;
			}
		}

		if (count($ids)) {
			Log::info("Dropping updates: " . join(', ', $ids));

			Update::withTrashed()->whereIn('id', $ids)->forceDelete();
		}

		$version = (new Version(new Carbon()))->setEntity($page);
		$result = (new Page\Comparator())->compareLast($version);

		if ($result) {
			if ($result->equals()) {
				$version->delete();
			}

			$page->size = $result->proclaimedSize();
			$page->save();
		}

		return json_encode([
			'version' => $version->__toString(),
			'result' => $result ? 'ok' : 'Comparision failed',
			'unchanged' => $result && $result->equals(),
		]);
	}

	public function getVersions(Page $page) {
	}

	public function getDownload(Request $request, Page $page, Version $version) {
		$parameters = $request->route()->parameters();

		$transformer = new Transforms();
		$transforms = $transformer->filterTransforms($parameters);

		if ($transforms) {
			$downloadable = $transformer->apply($transforms, $version->downloadable());

			return new DownloadWorker($downloadable);
		}

		return $this->viewDownload(compact('page', 'version'));
	}

}

class UpdatesGroupper {

	const MONTH_FORMAT = 'F, Y';

	/**
	 * @var PageUpdate[]
	 */
	protected $updates;

	/**
	 * @var PageUpdate[][]
	 */
	private $monthful = [];

	private $versions = [];

	/**
	 * @var Page
	 */
	private $page;

	/**
	 * @param Page $page
	 * @param PageUpdate[] $updates
	 */
	function __construct(Page $page, array $updates) {
		$this->page = $page;
		$this->updates = $updates;
	}

	/**
	 * @return string[]
	 */
	function months() {
		foreach ($this->updates as $update) {
			$version = $update->pageVersion();

			if (!PageUtils::exists($version->resolver())) {
				continue;
			}

			$this->versions[] = [$version, $update];

			$this->monthful[$this->month($update)][] = $update;
		}

		return array_keys($this->monthful);
	}

	function monthful($month): array {
		return $this->monthful[$month] ?? [];
	}

	/**
	 * @param Version $version
	 * @return Version[][]
	 */
	function prior(Version $version) {
		$from = $version->timestamp();
		$r = [];

		/**
		 * @var Version $ver
		 * @var PageUpdate $upd
		 */
		foreach ($this->versions as [$ver, $upd]) {
			if ($from > $ver->timestamp()) {
				$r[$this->month($upd)][] = $ver;
			}
		}

		return $r;
	}

	/**
	 * @param PageUpdate $update
	 * @return string
	 */
	function month(PageUpdate $update): string {
		return title_case($update->created_at->format(static::MONTH_FORMAT));
	}

	function flow() {
		$versions = [];

		foreach ($this->months() as $month) {
			foreach ($this->monthful($month) as $update) {
				$version = $update->pageVersion();
				$versions[$month][] = [$version, $update, $this->prior($version)];
			}
		}

		return $versions;
	}

}
