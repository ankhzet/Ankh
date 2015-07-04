<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Entity\Filters\RelationFilter;
	use Ankh\Entity\Filters\LetterFilter;
	use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

	use Ankh\Author;
	use Ankh\Crumbs as Breadcrumbs;

	class AuthorsController extends Controller {
		const AUTHORS_PER_PAGE = 20;

		private $author = null;

		public function __construct(Author $author, Breadcrumbs $crumbs) {
			$this->author = $author;
		}

		/**
		* Display a listing of the authors.
		*
		* @return Response
		*/
		public function index(Request $request) {
			$isAjax = $request->ajax();

			$letterFilter = new LetterFilter($request->get('letter'));

			if (!$isAjax)
				$letters = $letterFilter->lettersUsage($this->author);

			if ($letterFilter->letter() !== null)
				$this->author->filterWith($letterFilter);

			$this->author->orderWith(new AuthorOrderingDescriptor());

			$authors = $this->author->paginate(self::AUTHORS_PER_PAGE);

			if ($isAjax)
				return $authors;

			if ($letterFilter)
				$authors->appends(['letter' => $letterFilter->letter()]);

			return view('authors.index', compact('authors', 'letters'));
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

	class AuthorOrderingDescriptor extends OrderingDescriptor {

		public function __construct($direction = 'asc', $collumn = 'fio') {
			$this->direction = $direction;
			$this->collumn = $collumn;
		}

	}
