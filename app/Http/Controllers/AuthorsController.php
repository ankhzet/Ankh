<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Pagination\paginator;

	use Ankh\Http\Requests;

	use Ankh\Author;

	class AuthorsController extends Controller {
		const AUTHORS_PER_PAGE = 20;

		/**
		* Display a listing of the resource.
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

			return view('authors.list', ['authors' => $authors, 'letters' => $summary]);
		}

		/**
		* Show the form for creating a new resource.
		*
		* @return Response
		*/
		public function create() {
		//
		}

		/**
		* Store a newly created resource in storage.
		*
		* @return Response
		*/
		public function store() {
		//
		}

		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function show(Author $author) {
		//
		}

		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function edit($id) {
		//
		}

		/**
		* Update the specified resource in storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function update($id) {
		//
		}

		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function destroy($id) {
		//
		}
	}
