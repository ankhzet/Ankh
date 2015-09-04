<?php namespace Ankh\Entity;

use Ankh\Contracts\AuthorRepository as AuthorRepositoryContract;

use Ankh\Entity\OrderingDescriptors\OrderingDescriptor;

class AuthorRepositoryEloquent extends EntityRepositoryEloquent implements AuthorRepositoryContract {

	public function __construct(\Ankh\Author $model) {
		$this->setModel($model);
	}

	public function order() {
		$this->applyOrdering(new AuthorOrderingDescriptor());
	}

}

class AuthorOrderingDescriptor extends OrderingDescriptor {

	public function __construct($direction = 'asc', $collumn = 'fio') {
		$this->direction = $direction;
		$this->collumn = $collumn;
	}

}
