<?php namespace Ankh\Contracts;

interface EntityRepository {

	public function all($columns = array('*'));

	public function newInstance(array $attributes = array());

	public function paginate($perPage = 0, $columns = array('*'));

	public function create(array $attributes);

	public function find($id, $columns = array('*'));

	public function updateWithIdAndInput($id, array $input);

	public function destroy($id);

	public function subRepository($id);


	public function entriesPerPage();

	public function model();

	public function filters();

	public function addFilter($name, Filter $filter);

	public function filter($name);

	public function appendFiltersToPaginator($paginator);

	public function applyFilters();

	public function applyOrdering(OrderingDescriptor $d);

}
