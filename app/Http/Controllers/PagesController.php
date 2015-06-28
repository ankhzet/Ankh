<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Author;
	use Ankh\Group;
	use Ankh\Page;

	class PagesController extends Controller {
		const PAGES_PER_PAGE = 20;

		/**
		 * Display a listing of the page entities.
		 *
		 * @return Response
		 */
		public function index(Request $request) {
			$pages = Page::orderBy('title')->paginate(self::PAGES_PER_PAGE);
			return view('pages.index', compact('pages'));
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

