<?php namespace Ankh\Http\Controllers;

use Ankh\Http\Requests\PageRequest;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\PageRepository;
use Ankh\Crumbs as Breadcrumbs;

use Ankh\Version;

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

		$text = $page->version()->contents();

		return $this->viewShow(compact('page', 'text', 'exclude'));
	}

	/**
	 * Show the form for editing the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function edit($page) {
		$group = pick_arg(Page::class) ?: new Page;
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

	public function getVersions(Page $page) {
	}

	public function getDownload(Page $page) {
	}

}
