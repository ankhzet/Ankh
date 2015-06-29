<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Author;
	use Ankh\Crumbs as Breadcrumbs;

	class AuthorsController extends Controller {
		const AUTHORS_PER_PAGE = 20;
		public function __construct(Breadcrumbs $crumbs) {

		}

		/**
		* Display a listing of the authors.
		*
		* @return Response
		*/
		public function index(Request $request) {
			$letterGetter = 'ucase(substring(`fio`, 1, 1)) as `letter`';

			$authors = Author::selectRaw("*, {$letterGetter}");

			if ($letter = $request->get('letter')) {
				$authors = \DB::table(\DB::raw("({$authors->toSql()}) as a"))
					->mergeBindings($authors->getQuery());

				$authors = $authors->where('letter', '=', $letter);
			}

			$authors = $authors
				->orderBy('letter')
				->paginate(self::AUTHORS_PER_PAGE);

			if ($letter)
				$authors->appends(['letter' => $letter]);

			if ($request->ajax())
				return $authors;

			$summary = Author::selectRaw("{$letterGetter}, count(`id`) as `count`")
				->groupBy('letter')
				->orderBy('letter')
				->lists('count', 'letter');

			return view('authors.index', compact('authors', 'summary'));
		}

		/**
		* Show the form for creating a new author entry.
		*
		* @return Response
		*/
		public function create() {

		}

		/**
		* Store a newly created author entry in storage.
		*
		* @return Response
		*/
		public function store() {

		}

		/**
		* Display the specified author.
		*
		* @param  Author  $author
		* @return Response
		*/
		public function show(Author $author) {

			return view('authors.show', compact('author'));
		}

		/**
		* Show the form for editing the specified author entry.
		*
		* @param  Author  $author
		* @return Response
		*/
		public function edit(Author $author) {

		}

		/**
		* Update the specified author entry in storage.
		*
		* @param  Author  $author
		* @return Response
		*/
		public function update(Author $author) {

		}

		/**
		* Remove the specified author entry from storage.
		*
		* @param  Author  $author
		* @return Response
		*/
		public function destroy(Author $author) {

		}
	}
