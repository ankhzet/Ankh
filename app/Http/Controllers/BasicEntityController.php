<?php

namespace Ankh\Http\Controllers;

use Illuminate\Http\Request;

use Ankh\Http\Requests;

use Ankh\Entity\Filters\RelationFilter;
use Ankh\Entity\Filters\LetterFilter;
use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

use Ankh\Crumbs as Breadcrumbs;

class BasicEntityController extends Controller {
	const F_LETER = 'letter';
	protected $model;
	protected $filters = [];

	protected function model() {
		return $this->model;
	}

	protected function setModel($model) {
		$this->model = $model;
	}

	protected function entriesPerPage() {
		return 20;
	}


	protected function addRelationFilter($column, $value) {
		if ($value)
			$this->filters[$column] = new RelationFilter($column, $value);
	}

	protected function addLetterFilter($letter) {
		$this->filters[self::F_LETER] = new LetterFilter($letter);
	}

	protected function letterFilter() {
		return $this->filters[self::F_LETER];
	}

	protected function appendFiltersToPaginator($paginator) {
		$appends = [];
		foreach ($this->filters as $key => $filter)
			$appends[$key] = $filter->paginationQueryFilter();

		$paginator->appends($appends);
	}

	protected function applyFilters() {
		foreach ($this->filters as $filter) {
			if ($filter->shouldApply())
				$this->model->filterWith($filter);
		}
	}

	protected function lettersUsage() {
		return $this->letterFilter()->lettersUsage($this->model, $this->filters);
	}

	protected function applyOrdering(OrderingDescriptor $d) {
		$this->model->orderWith($d);
	}

	protected function paginate() {
		return $this->model->paginate($this->entriesPerPage());
	}

}
