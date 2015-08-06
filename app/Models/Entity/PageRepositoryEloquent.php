<?php namespace Ankh\Entity;

use Ankh\Contracts\PageRepository as PageRepositoryContract;

class PageRepositoryEloquent extends EntityRepositoryEloquent implements PageRepositoryContract {

	public function __construct(\Ankh\Page $model) {
		$this->setModel($model);
	}

	public function subRepository($id) {
		throw new Exception(get_class($this) . ' has no subrepositories');
	}

}
