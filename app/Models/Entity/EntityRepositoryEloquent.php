<?php namespace Ankh\Entity;

use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Ankh\Contracts\EntityRepository as EntityRepositoryContract;

use Ankh\Contracts\Filter;
use Ankh\Contracts\OrderingDescriptor;

class EntityRepositoryEloquent implements EntityRepositoryContract {

	use \Ankh\Traits\Entity\Repository\RelationFilterTrait;
	use \Ankh\Traits\Entity\Repository\LetterFilterTrait;
	use \Ankh\Traits\Entity\Repository\OrderingTrait;

	const C_PERPG = 20;
	protected $model;
	protected $filters = [];

	public function all($columns = array('*')) {
		return $this->model->all($columns);
	}

	public function pluck(array $columns) {
		return $this->all()->pluck($columns);
	}

	public function newInstance(array $attributes = array()) {
		return $this->model->newInstance($attributes);
	}

	public function paginate($perPage = 0, $columns = array('*')) {
		return $this->model->paginate($perPage ?: $this->entriesPerPage(), $columns);
	}

	public function create(array $attributes) {
		return $this->model->create($attributes);
	}

	public function find($id, $columns = array('*')) {
		return $this->model->withTrashed()->findOrFail($id, $columns);
	}

	public function findEvenTrashed($id, array $attributes = ['*']) {
		$model = $this->model();

		$entity = $model->newQuery()->where('id', $id)->withTrashed()->first($attributes);

		if (!$entity)
			throw (new ModelNotFoundException)->setModel(get_class($model));

		return $entity;
	}

	public function updateEvenTrashed($model, $data) {
		array_forget($data, $model::UPDATED_AT);

		if (!$model->exists)
			$model = $model->newQuery()->where('id', $model->id)->withTrashed();

		// dd($model, $data);
		return $model->update($data) !== false;
	}

	public function updateWithIdAndInput($id, array $input) {
		$entry = $this->model->findOrFail($id);
		return $entry->update($input);
	}

	public function destroy($id) {
		return $this->model->destroy($id);
	}

	public function subRepository($id) {
		throw new Exception(get_class($this) . ' has no subrepositories');
	}


	/*
	 * Meat'&'flesh
	 *
	 */

	public function model() {
		return $this->model;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function repositoryName() {
		return 'entity';
	}

	public function entriesPerPage() {
		return Config::get('common::' . $this->repositoryName() .'.page-entries') ?: self::C_PERPG;
	}

	public function addFilter($name, Filter $filter) {
		$this->filters[$name] = $filter;
	}

	public function filter($name) {
		return isset($this->filters[$name]) ? $this->filters[$name] : null;
	}

	public function filters() {
		return $this->filters;
	}

	public function appendFiltersToPaginator($paginator) {
		$appends = [];
		foreach ($this->filters as $key => $filter)
			$appends[$key] = $filter->paginationQueryFilter();

		$paginator->appends($appends);
	}

	public function applyFilters() {
		foreach ($this->filters as $filter) {
			if ($filter->shouldApply())
				$this->model->filterWith($filter);
		}
	}

	public function applyOrdering(OrderingDescriptor $d) {
		$this->model->orderWith($d);
	}

}
