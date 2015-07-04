<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Author;
	use Ankh\Group;
	use Ankh\Page;

	use Ankh\Entity\Filters\RelationFilter;
	use Ankh\Entity\Filters\LetterFilter;
	use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

	use Ankh\Crumbs as Breadcrumbs;

	class PagesController extends Controller {
		const PAGES_PER_PAGE = 2;

		private $page = null;

		public function __construct(Page $page, Breadcrumbs $breadcrumbs) {
			$this->page = $page;
		}

		/**
		 * Display a listing of the page entities.
		 *
		 * @return Response
		 */
		public function index(Request $request, Author $author, Group $group) {
			$isAjax = $request->ajax();

			$relationFilter = null;
			if ($author->id)
				$relationFilter = new RelationFilter('author', $author->id);
			else {
				if ($group->id) {
					$author= $group->author;
					$relationFilter = new RelationFilter('group', $group->id);
				}
			}

			$letterFilter = new LetterFilter($request->get('letter'));

			if ($relationFilter)
				$this->page->filterWith($relationFilter);

			if ($letterFilter->letter() !== null)
				$this->page->filterWith($letterFilter);

			$letters = $letterFilter->lettersUsage($this->page, $relationFilter ? [$relationFilter] : []);

			$this->page->orderWith(new OrderingDescriptor());

			$pages = $this->page->paginate(self::PAGES_PER_PAGE);

			if ($letterFilter)
				$pages->appends(['letter' => $letterFilter->letter()]);

			return view('pages.index', compact('author', 'group', 'pages', 'letters'));
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
		public function show(Page $page) {
			$author = $page->author;
			$group = $page->group;
			return view('pages.show', compact('author', 'group', 'page'));
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
