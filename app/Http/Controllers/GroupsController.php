<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Author;
use Ankh\Group;

use Ankh\Contracts\GroupRepository;
use Ankh\Crumbs as Breadcrumbs;

class GroupsController extends Controller {
	const GROUPS_PER_PAGE = 5;
	protected $m;

	public function __construct(GroupRepository $groups, Breadcrumbs $breadcrumbs) {
		$this->m = $groups;
	}

	/**
	 * Display a listing of the group entities.
	 *
	 * @return Response
	 */
	public function index(Request $request, Author $author) {
		$isAjax = $request->ajax();

		$this->m->addRelationFilter('author', $author->id);
		$this->m->addLetterFilter($request->get('letter'));
		$this->m->applyFilters();
		if (!$isAjax)
			$letters = $this->m->lettersUsage();

		$this->m->order();

		$groups = $this->m->paginate(self::GROUPS_PER_PAGE);

		if ($isAjax)
			return $groups;

		$this->m->appendFiltersToPaginator($groups);

		return view('groups.index', compact('author', 'groups', 'letters'));
	}

	/**
	 * Show the form for creating a new group entity.
	 *
	 * @return Response
	 */
	public function create() {
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
	public function show(Group $group) {
		$author = $group->author;
		return view('groups.show', compact('author', 'group'));
	}

	/**
	 * Show the form for editing the specified group entity.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function edit(Group $group) {

	}

	/**
	 * Update the specified group entity in storage.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function update(Group $group) {

	}

	/**
	 * Remove the specified group entity from storage.
	 *
	 * @param  Group $group
	 * @return Response
	 */
	public function destroy(Group $group) {

	}
}
