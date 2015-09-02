<?php namespace Ankh\Http\Controllers;

use Ankh\Entity;

use Ankh\Contracts\UpdateRepository;
use Ankh\Crumbs as Breadcrumbs;

class UpdatesController extends RestfulController {
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
	public function index() {
		$entity = pick_arg(Entity::class);
		if ($entity)
			$this->m->setEntity($entity);

		$updates = $this->m->paginate(self::UPDATES_PER_PAGE);

		return view('updates.index', compact('updates'));
	}

	/**
	 * Display the specified page entity.
	 *
	 * @param  Page $page
	 * @return Response
	 */
	public function show() {
		$update = pick_arg(\Ankh\Update::class);
		return $this->viewShow(compact('update'));
	}

	protected function innerRedirect($action, $entity = null) {
		return parent::innerRedirect('index', $entity);
	}

}
