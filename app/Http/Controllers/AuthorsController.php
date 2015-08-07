<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;

use Ankh\Contracts\AuthorRepository;
use Ankh\Crumbs as Breadcrumbs;

class AuthorsController extends Controller {
	const AUTHORS_PER_PAGE = 20;
	protected $m;

	public function __construct(AuthorRepository $authors, Breadcrumbs $crumbs) {
		$this->m = $authors;
	}

	/**
	* Display a listing of the authors.
	*
	* @return Response
	*/
	public function index(Request $request) {
		$isAjax = $request->ajax();

		$this->m->addLetterFilter($request->get('letter'));
		$this->m->applyFilters();
		if (!$isAjax)
			$letters = $this->m->lettersUsage();

		$this->m->order();

		$authors = $this->m->paginate(self::AUTHORS_PER_PAGE);

		if ($isAjax)
			return $authors;

		$this->m->appendFiltersToPaginator($authors);

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

	public function getChronology(Author $author) {

	}

	public function getCheck(Author $author) {

	}

	public function getTraceUpdates(Author $author) {

	}

}
