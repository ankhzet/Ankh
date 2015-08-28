<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;
use Ankh\Group;

use Ankh\Contracts\GroupRepository;
use Ankh\Crumbs as Breadcrumbs;

class GroupsController extends RestfulController {
	protected $m;

	protected $filters = ['letter', 'author'];
	protected $filtersMapping = ['author' => 'authors'];

	public function __construct(GroupRepository $groups, Breadcrumbs $breadcrumbs) {
		$this->m = $groups;
	}

	protected function repository() {
		return $this->m;
	}

	/**
	 * Display a listing of the group entities.
	 *
	 * @return Response
	 */
	public function index() {
		$groups = parent::index();

		if (self::isApiCall())
			return response()->json($pages);

		$exclude = $this->hasFilters($this->filters);

		$author = pick_arg(Author::class);
		return $this->viewIndex(compact('groups', 'exclude', 'author'));
	}

	/**
	 * Store a newly created group entity in storage.
	 *
	 * @return Response
	 */
	public function store() {

	}

	/**
	 * Display the specified group entity.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function show() {
		$group = pick_arg(Group::class);
		$author = $group->author;
		return $this->viewShow(compact('author', 'group'));
	}

	/**
	 * Show the form for editing the specified group entity.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function edit() {
		$group = pick_arg(Group::class) ?: new Group;
		if (!$group->author) {
			$author = pick_arg(Author::class);

			if (!$author)
				throw new \Exception('Group can be created only if author is specified');

			$group->author = $author;
		}

		return $this->viewEdit(compact('group'));
	}

	/**
	 * Remove the specified group entity from storage.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function destroy(Group $group) {
		if ($group->delete())
			return self::plain('Deleted');
		else
			throw new Exception("Deletion failed");
	}

	public function getChronology(Author $author) {

	}

}
