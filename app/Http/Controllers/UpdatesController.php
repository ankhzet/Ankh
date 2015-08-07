<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\PageRepository;
use Ankh\Crumbs as Breadcrumbs;

class UpdatesController extends Controller {
	/**
	 * Display a listing of the page entities.
	 *
	 * @return Response
	 */
	public function index(Request $request, Author $author, Group $group) {
		return self::plain('updates');
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

}

