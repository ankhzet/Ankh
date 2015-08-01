<?php

namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

use Ankh\Author;
use Ankh\Group;

use Ankh\Crumbs as Breadcrumbs;

class GroupsController extends BasicEntityController {
	const GROUPS_PER_PAGE = 5;

	public function __construct(Group $group, Breadcrumbs $breadcrumbs) {
		$this->setModel($group);
	}


	protected function entriesPerPage() {
		return self::GROUPS_PER_PAGE;
	}


	/**
	 * Display a listing of the group entities.
	 *
	 * @return Response
	 */
	public function index(Request $request, Author $author) {
		$isAjax = $request->ajax();

		$this->addRelationFilter('author', $author->id);
		$this->addLetterFilter($request->get('letter'));
		$this->applyFilters();
		if (!$isAjax)
			$letters = $this->lettersUsage();

		$this->applyOrdering(new OrderingDescriptor());

		$groups = $this->paginate();

		if ($isAjax)
			return $groups;

		$this->appendFiltersToPaginator($groups);

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
