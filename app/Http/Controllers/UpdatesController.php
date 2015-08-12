<?php namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Entity;
use Ankh\Author;
use Ankh\Group;
use Ankh\Page;

use Ankh\Contracts\UpdateRepository;
use Ankh\Crumbs as Breadcrumbs;

class UpdatesController extends Controller {
	const UPDATES_PER_PAGE = 15;
	protected $m;

	public function __construct(UpdateRepository $updates, Breadcrumbs $breadcrumbs) {
		$this->m = $updates;
	}

	/**
	 * Display a listing of the update entities.
	 *
	 * @return Response
	 */
	public function index(Request $request, Entity $entity) {
		if ($entity->id)
			$this->m->setEntity($entity);

		$updates = $this->m->paginate(self::UPDATES_PER_PAGE);

		return view('updates.index', compact('updates'));
	}

	/**
	 * Display the specified update entity.
	 *
	 * @param  Update $update
	 * @return Response
	 */
	public function show(Update $update) {
		return view('updates.show', compact('update'));
	}

	/**
	 * Show the form for editing the specified update entity.
	 *
	 * @param  Update $update
	 * @return Response
	 */
	public function edit(Update $update) {

	}

	/**
	 * Update the specified update entity in storage.
	 *
	 * @param  Update $update
	 * @return Response
	 */
	public function update(Update $update) {

	}

	/**
	 * Remove the specified update entity from storage.
	 *
	 * @param  Uodate $update
	 * @return Response
	 */
	public function destroy(Update $update) {
		if ($update->delete())
			return Redirect::back(302)->withMessage('Deleted');
		else
			throw new Exception("Deletion failed");
	}
}
