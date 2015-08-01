<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Author;
	use Ankh\Group;
	use Ankh\Page;

	use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

	use Ankh\Crumbs as Breadcrumbs;

	class PagesController extends BasicEntityController {
		const PAGES_PER_PAGE = 10;


		public function __construct(Page $page, Breadcrumbs $breadcrumbs) {
			$this->setModel($page);
		}

		protected function entriesPerPage() {
			return self::PAGES_PER_PAGE;
		}


		/**
		 * Display a listing of the page entities.
		 *
		 * @return Response
		 */
		public function index(Request $request, Author $author, Group $group) {
			$isAjax = $request->ajax();

			$this->addRelationFilter('author', $author->id);
			$this->addRelationFilter('group', $group->id);
			$this->addLetterFilter($request->get('letter'));
			$this->applyFilters();
			if (!$isAjax)
				$letters = $this->lettersUsage();

			$this->applyOrdering(new OrderingDescriptor());

			$pages = $this->paginate();

			if ($isAjax)
				return $pages;

			$this->appendFiltersToPaginator($pages);

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
	}
