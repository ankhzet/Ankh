<?php namespace Ankh\Contracts;

interface Entity extends Filterable, Orderable {

	public function underlyingQuery($setQuery = null);

	public function paginate($perPage = null);

}
