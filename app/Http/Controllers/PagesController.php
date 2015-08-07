<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\PageRepository;
use Ankh\Crumbs as Breadcrumbs;

class PagesController extends Controller {
	const PAGES_PER_PAGE = 10;
	protected $m;

	public function __construct(PageRepository $pages, Breadcrumbs $breadcrumbs) {
		$this->m = $pages;
	}

	/**
	 * Display a listing of the page entities.
	 *
	 * @return Response
	 */
	public function index(Request $request, Author $author, Group $group) {
		$isAjax = $request->ajax();

		$this->m->addRelationFilter('author', $author->id);
		$this->m->addRelationFilter('group', $group->id);
		$this->m->addLetterFilter($request->get('letter'));
		$this->m->applyFilters();
		if (!$isAjax)
			$letters = $this->m->lettersUsage();

		$this->m->order();

		$pages = $this->m->paginate(self::PAGES_PER_PAGE);

		if ($isAjax)
			return $pages;

		$this->m->appendFiltersToPaginator($pages);

		$exclude = [];
		if ($author->id) $exclude[] = 'author';
		if ($group->id) $exclude[] = 'group';

		return view('pages.index', compact('author', 'group', 'pages', 'letters', 'exclude'));
	}

	/**
	 * Show the form for creating a new page entity.
	 *
	 * @return Response
	 */
	public function create() {
	}

	/**
	 * Store a newly created page entity in storage.
	 *
	 * @return Response
	 */
	public function store() {

	}

	/**
	 * Display the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function show(Author $author, Group $group, Page $page) {
		$author = $page->author;
		$group = $page->group;
		$reader = (string)$page;
		return view('pages.show', compact('author', 'group', 'page', 'reader'));
	}

	/**
	 * Show the form for editing the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function edit(Page $page) {

	}

	/**
	 * Update the specified page entity in storage.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function update(Page $page) {

	}

	/**
	 * Remove the specified page entity from storage.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function destroy(Page $page) {

	}

	public function getVersions(Author $author) {

	}

}
