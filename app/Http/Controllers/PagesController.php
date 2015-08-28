<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\PageRepository;
use Ankh\Crumbs as Breadcrumbs;

use PageUtils;

class PagesController extends RestfulController {
	protected $m;

	protected $filters = ['letter', 'author', 'group'];
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
			return response()->json($pages);

		$exclude = $this->hasFilters($this->filters);

		return $this->viewIndex(compact('pages', 'exclude'));
	}

	/**
	 * Display the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function show() {
		list($author, $group, $page) = pick_arg(Author::class, Group::class, Page::class);
		$exclude = view_excludes(['author' => $author, 'group' => $group]);

		$content = PageUtils::contents($page->resolver());

		return $this->viewShow(compact('page', 'content', 'exclude'));
	}

	/**
	 * Show the form for editing the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function edit($page) {

	}

	/**
	 * Update the specified page entity in storage.
	 *
	 * @param  PageRequest $request
	 * @return Response
	 */

	public function getVersions(Page $page) {
	}

	public function getDownload(Page $page) {
	}

}
