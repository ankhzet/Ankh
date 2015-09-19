<?php namespace Ankh\Entity;

use Ankh\Contracts\UpdateRepository as UpdateRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use Ankh\Entity;
use Ankh\Update;
use Ankh\AuthorUpdate;
use Ankh\GroupUpdate;
use Ankh\PageUpdate;

use Jenssegers\Date\Date;

class UpdateRepositoryEloquent extends EntityRepositoryEloquent implements UpdateRepositoryContract {

	protected $entity;

	public function __construct(Update $model) {
		$this->setModel($model);
	}

	public function setEntity(Entity $entity) {
		$this->entity = $entity;

		if ($this->entity) {
			$this->setModel(app($entity->updateClass()));
			$this->model->underlyingQuery()->where('entity_id', $this->entity->id);
		}
	}

	public function order() {
	}

	public function paginate($perPage = 15, $columns = array('*')) {
		$this->model->underlyingQuery()->orderBy('created_at', 'desc')->orderBy('id', 'desc');

		$perPage = $perPage ?: $this->entriesPerPage();
		$pageName = 'page';
		$query = $this->model->underlyingQuery();
		$total = $query->getQuery()->getCountForPagination();

		$query->forPage(
			$page = Paginator::resolveCurrentPage($pageName),
			$perPage = $perPage ?: $this->model->model->getPerPage()
			);

		return new UpdatesPaginator($query->get($columns), $total, $perPage, $page, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => $pageName,
			]);
	}


}

class UpdatesPaginator extends LengthAwarePaginator {
	private $known = [];

	private $glue;

	protected function known() {
		if (!$this->known) {
			$this->known[] = new AuthorUpdate;
			$this->known[] = new GroupUpdate;
			$this->known[] = new PageUpdate;
		}
		return $this->known;
	}

	public function transform(Update $update) {
		$r_type = $update->updateType();

		$class = $update->entityClass();
		if ($class == Entity::class) {
			foreach ($this->known() as $model) {
				if ($model->updateType() == $r_type)
					return $model->newFromBuilder($update->getAttributes());
			}

			// throw new \Exception("Unknown update type [$r_type] for entity class [$class]");
		}

		return $update;
	}

	public function collect() {
		$u = [];
		foreach ($this as $update)
			$u[] = $this->transform($update);

		return $this->glue()->collect($u);
	}

	public function ago($date) {
		return date_ago(Date::createFromFormat('Y-m-d', $date));
	}

	public function author($id) {
		return $this->glue()->author($id);
	}

	function glue() {
		return $this->glue ?: ($this->glue = new UpdatesGroupping);
	}

}

