<?php namespace Ankh\Entity;

use Ankh\Contracts\EntityRepository as EntityRepositoryContract;

use Ankh\Contracts\Filter;
use Ankh\Contracts\OrderingDescriptor;
use Config;

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
		return $this->model->find($id, $columns);
	}

	public function updateWithIdAndInput($id, array $input) {
		$entry = $this->faqModel->findOrFail($id);
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
		return $this->filters[$name];
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
