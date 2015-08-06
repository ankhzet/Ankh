<?php namespace Ankh\Entity;

use Ankh\Contracts\GroupRepository as GroupRepositoryContract;

class GroupRepositoryEloquent extends EntityRepositoryEloquent implements GroupRepositoryContract {

	public function __construct(\Ankh\Group $model) {
		$this->setModel($model);
	}

	public function subRepository($id) {
		throw new Exception(get_class($this) . ' has no subrepositories');
	}

}
