<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests\GroupRequest;

use Ankh\Author;
use Ankh\Group;

use Ankh\Contracts\GroupRepository;
use Ankh\Crumbs as Breadcrumbs;

class GroupsController extends RestfulController {
	protected $m;

	protected $filters = ['letter', 'author', 'offset'];
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
			return response()->json(remap_for_json($groups->all()));

		$exclude = $this->hasFilters($this->filters);

		$author = pick_arg(Author::class);
		return $this->viewIndex(compact('groups', 'exclude', 'author'));
	}

	/**
	 * Store a newly created group entity in storage.
	 *
	 * @return Response
	 */
	public function store(GroupRequest $request) {
		return parent::_store($request);
	}

	/**
	 * Display the specified group entity.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function show() {
		list($author, $group) = pick_arg(Author::class, Group::class);
		$exclude = view_excludes(['author' => $author]);

		return $this->viewShow(compact('group', 'exclude'));
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
	 * Update the specified group entity in storage.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function update(GroupRequest $request) {
		return $this->_update($request, pick_arg(Group::class));
	}

	public function getChronology(Author $author) {

	}

}
