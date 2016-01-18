<?php namespace Ankh\Http\Controllers;

use Ankh\Entity;
use Ankh\Update;

use Ankh\Contracts\UpdateRepository;
use Ankh\Crumbs as Breadcrumbs;

class UpdatesController extends RestfulController {
	const ENTITIES_PER_PAGE = 40;
	protected $m;

	protected $filters = ['offset'];

	public function __construct(UpdateRepository $updates, Breadcrumbs $breadcrumbs) {
		$this->m = $updates;
	}

	protected function repository() {
		return $this->m;
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

		$updates = parent::index();
		if (self::isApiCall())
			return response()->json(remap_for_json($updates->all()));

		$exclude = $this->hasFilters($this->filters);

		return $this->viewIndex(compact('updates', 'exclude'));
	}

	/**
	 * Display the specified update entity.
	 *
	 * @return Response
	 */
	public function show() {
		$update = pick_arg(Update::class);
		return $this->viewShow(compact('update'));
	}

	protected function innerRedirect($action, $entity = null) {
		return parent::innerRedirect('index', $entity);
	}

}
