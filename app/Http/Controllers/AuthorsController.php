<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests\AuthorRequest;
use Ankh\Http\Requests\AuthorCreateRequest;

use Ankh\Author;
use Ankh\Synk\AuthorUtils;

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

	public function getCheck(Request $request) {
		$selected = 0;
		foreach ($request->query() as $key => $value)
			if ($value == '' && (string)intval($key) == "$key") {
				$selected = intval($key);
				break;
			}

		$id = $selected;
		if (!$id) {
			$queue = $this->pickAuthor();
			$id = intval(array_shift($queue));
		}

		$stats = [];

		if (!$selected)
			$stats['pending'] = $queue;

		if ($id) {
			$author = Author::find($id);

			if ($author) {

				$stats = array_merge_recursive($stats, $this->checkAuthor($author));
			} else
				throw new Exception("Author with ID $id not found");

		}

		if ($id && !$selected)
			header("Refresh: 2");

		return response()->json($stats);
	}

	function pickAuthor() {
		$queue = session('to_check') ?: [];

		$check = intval(array_shift($queue));
		if (!($queue || $check))
			$queue = $this->repository()->pluck(['id'])->all();

		sort($queue);

		session()->set('to_check', $queue);
		session()->save();

		return array_merge([$check], $queue);
	}

	function checkAuthor(Author $author) {
		$stats = [];
		$util = new AuthorUtils;
		$stats['changes'] = $util->check($author);
		$stats['check'] = array_only($author->attributesToArray(), ['id', 'link']);
		return $stats;
	}

	public function getTraceUpdates(Author $author) {

	}

}
