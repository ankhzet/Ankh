<?php

	namespace Ankh\Http\Controllers;

	use Illuminate\Http\Request;

	use Ankh\Http\Requests;

	use Ankh\Entity\Filters\RelationFilter;
	use Ankh\Entity\Filters\LetterFilter;
	use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

	use Ankh\Author;
	use Ankh\Group;

	use Ankh\Crumbs as Breadcrumbs;

	class GroupsController extends Controller {
		const GROUPS_PER_PAGE = 2;

		private $group = null;

		public function __construct(Group $group, Breadcrumbs $breadcrumbs) {
			$this->group = $group;
		}

		function applyFiltersToPaginator($paginator, array $filters) {
			$appends = [];
			foreach ($filters as $key => $filter)
				$appends[$key] = $filter->paginationQueryFilter();

			$paginator->appends($appends);
		}

		/**
		 * Display a listing of the group entities.
		 *
		 * @return Response
		 */
		public function index(Request $request, Author $author) {
			$isAjax = $request->ajax();

			$filters = [];

			if ($author->id)
				$filters['author'] = new RelationFilter('author', $author->id);

			$filters['letter'] = new LetterFilter($request->get('letter'));

			foreach ($filters as $filter)
				if ($filter->shouldApply())
					$this->group->filterWith($filter);

			if (!$isAjax)
				$letters = $filters['letter']->lettersUsage($this->group, $filters);

			$this->group->orderWith(new OrderingDescriptor());

			$groups = $this->group->paginate(self::GROUPS_PER_PAGE);

			if ($isAjax)
				return $groups;

			$this->applyFiltersToPaginator($groups, array_except($filters, 'author'));

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
