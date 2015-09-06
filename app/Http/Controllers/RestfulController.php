<?php namespace Ankh\Http\Controllers;

use Request;

use Ankh\Contracts\EntityRepository;
use Ankh\Entity;

use Ankh\Http\Middleware\Subdomens;
use Ankh\Http\Requests\EntityRequest;
use Ankh\Http\Requests\AdminRoleRequest;

class RestfulController extends Controller {
	const ENTITIES_PER_PAGE = 10;

	protected $filters = [];
	protected $filtersMapping = [];
	protected $statistics = [];

	protected $viewsRoot;

	protected function setRepository($repository) {
		$this->repository = $repository;
	}

	protected function repository() {
		throw new Exception('No assigned repository');
	}

	public static function isApiCall() {
		return Subdomens::is('api');
	}

	function filterRouteMapping($name) {
		return isset($this->filtersMapping[$name]) ? $this->filtersMapping[$name] : $name;
	}

	function hasFilter($name) {
		if ($id = Request::get($name))
			return $id;

		if ($entity = Request::route($this->filterRouteMapping($name)))
			return $entity->id;

		return false;
	}

	function hasFilters($names) {
		return array_filter($names, function ($name) { return $this->hasFilter($name); });
	}

	function filterByEntity($entityName) {
		return $this->m->addRelationFilter($entityName, $this->hasFilter($entityName));
	}

	function filterStatistics() {
		if (!($this->statistics || self::isApiCall())) {
			$this->statistics['letters'] = $this->repository()->lettersUsage();
		}
		return $this->statistics;
	}

	function applyFilters() {
		$repository = $this->repository();
		foreach ($this->filters as $filter)
			switch ($filter) {
				case 'letter':
					$repository->addLetterFilter(Request::get('letter'));
					break;

				default:
					$this->filterByEntity($filter);
			}
		$repository->applyFilters();

		$this->filterStatistics();
	}

	protected function innerRedirect($action, $entity = null) {
		return redirect(route($this->name() . '.' . $action, $entity));
	}

	/**
	 * Return requested entities paginator.
	 *
	 * @return Paginator
	 */
	public function index() {
		$this->applyFilters();

		$this->repository()->order();

		$paginator = $this->repository()->paginate(static::ENTITIES_PER_PAGE);

		$this->repository()->appendFiltersToPaginator($paginator);

		return $paginator;
	}

	/**
	 * Show the form for creating a new entity.
	 * Basically, just forwards call to "edit" method.
	 *
	 * @return Response
	 */
	public function create() {
		return call_user_func_array([$this, 'edit'], func_get_args());
	}

	/**
	* Store a newly created entity entry in storage.
	*
	* @param  EntityRequest $request
	* @return Response
	*/
	public function _store(EntityRequest $request) {
		$entity = $request->candidate();
		if ($entity->save())
			return $this->innerRedirect('show', $entity)->withMessage('Saved');

		throw new Exception('Failed to save');
	}

	/**
	* Update the specified entity entry in storage.
	*
	* @param  EntityRequest  $request
	* @return Response
	*/
	public function _update(EntityRequest $request, Entity $entity) {
		if ($request->deleted())
			$entity->delete();
		else
			if ($entity->trashed())
				$entity->restore();

		if ($this->m->updateEvenTrashed($entity, $request->data()))
			return $this->innerRedirect('show', $entity)->withMessage('Updated');

		throw new \Exception('Update failed');
	}

	/**
	* Remove the specified entity entry from storage.
	* Actually, just marks entity as "deleted", but keeps in DB.
	*
	* @param  AdminRoleRequest $request
	* @param  Entity           $entity
	* @return Response
	*/
	public function destroy(AdminRoleRequest $request) {
		$entity = pick_arg(get_class($this->repository()->model()));
		if ($entity->delete())
			return $this->innerRedirect('show', $entity)->withMessage('Deleted');

		throw new Exception("Deletion failed");
	}

	/**
   * View helpers.
	 */

	protected function name() {
		return strtolower(str_replace('Controller', '', class_basename($this)));
	}

	protected function viewsRoot() {
		if (!$this->viewsRoot)
			$this->viewsRoot = $this->name();

		return $this->viewsRoot;
	}

	public function __call($method, $args) {
		if (substr($method, 0, 4) == 'view') {
			$arg = @$args[0] ?: [];

			if (self::isApiCall()) {
				$relations = !!\Request::get('relations');

				if (is_array($arg)) {
					$entity = pick_arg($arg, get_class($this->repository()->model()));
					if ($entity) {
						if ($relations)
							pick_relations($entity);

						$arg = $entity;
					}
				}
				return response()->json(remap_for_json($arg, $relations));
			}

			$view = strtolower(substr($method, 4));
			return view("{$this->viewsRoot()}.{$view}", $arg);
		}

		return parent::__call($method, $args);
	}

	protected function viewIndex(array $arguments = []) {
		return view("{$this->viewsRoot()}.index", array_merge($this->filterStatistics(), $arguments));
	}

}

function pick_relations(Entity $entity) {
	$stack = debug_backtrace(0, 4);

	foreach (array_slice($stack, 1) as $frame)
		if (starts_with($frame['function'], ['__call', 'view'])) {
			continue;
		} else {
			array_filter(array_map(function ($arg) use ($entity) {
				if (($arg == $entity) || !($arg instanceof Entity))
					return null;

				$name = strtolower(class_basename($arg));
				if (method_exists($entity, $name))
					$entity->{$name} = $arg;

				return $arg;
			}, $frame['args']));
			break;
		}
}

function remap_for_json($output, $withRelations = false) {
	if (!isset($output))
		return false;

	$methods = [false => 'attributesToArray', true => 'toArray'];
	if (method_exists($output, ($arrayable = $methods[!!$withRelations]))) {
		$output = $output->{$arrayable}();
	}

	return is_array($output) ? array_filter(array_map(function ($param) use ($withRelations) {
		return remap_for_json($param, $withRelations);
	}, $output)) : $output;
}
