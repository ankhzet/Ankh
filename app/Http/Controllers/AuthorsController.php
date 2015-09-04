<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests\AuthorRequest;
use Ankh\Http\Requests\AuthorCreateRequest;

use Ankh\Author;
use Ankh\AuthorUtils;

use Ankh\Contracts\AuthorRepository;
use Ankh\Crumbs as Breadcrumbs;
use Ankh\Http\Requests\AdminRoleRequest;

class AuthorsController extends RestfulController {
	protected $m;

	protected $filters = ['letter'];

	public function __construct(AuthorRepository $authors, Breadcrumbs $crumbs) {
		$this->m = $authors;
	}

	protected function repository() {
		return $this->m;
	}

	/**
	* Display a listing of the authors.
	*
	* @return Response
	*/
	public function index() {
		$authors = parent::index();

		if (self::isApiCall())
			return response()->json($authors);

		return $this->viewIndex(compact('authors'));
	}

	/**
	* Store a newly created author entry in storage.
	*
	* @param  AuthorCreateRequest $request
	* @return Response
	*/
	public function store(AuthorCreateRequest $request) {
		return $this->_store($request);
	}

	/**
	* Display the specified author.
	*
	* @param  Author  $author
	* @return Response
	*/
	public function show() {
		$author = pick_arg(Author::class);
		return $this->viewShow(compact('author'));
	}

	/**
	* Show the form for editing the specified author entry.
	*
	* @param  Author  $author
	* @return Response
	*/
	public function edit() {
		$author = pick_arg(Author::class) ?: new Author;
		return $this->viewEdit(compact('author'));
	}

	/**
	* Update the specified author entry in storage.
	*
	* @param  AuthorRequest  $request
	* @return Response
	*/
	public function update(AuthorRequest $request) {
		return $this->_update($request, pick_arg(Author::class));
	}

	public function getCheck(Author $author) {
		$toCheck = session('to_check') ?: [];
		if (!$toCheck) {
			$toCheck = Author::all()->pluck(['id'])->all();
			session()->set('to_check', $toCheck);
			session()->save();
		}

		$util = new AuthorUtils;
		$stats = $util->check($author);

		dump($stats);

		$toCheck = array_merge(array_diff($toCheck, [$author->id]), []);
		session()->set('to_check', $toCheck);
		session()->save();

		$next = @$toCheck[0];

		$content = 'Pending: ' . join(', ', $toCheck);
		if ($next) {

			$next = Author::find($next);
			if ($next) {
				$content .= '<br/><br/><center>Continue [check ' . \HTML::link(route('authors.check', $next), $next->fio) . ']</center>';
			}
		}

		return view('home', compact('content'));
	}

	public function getTraceUpdates(Author $author) {

	}

}
