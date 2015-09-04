<?php namespace Ankh\Entity;

use Ankh\Contracts\UpdateRepository as UpdateRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use Ankh\Update;
use Ankh\AuthorUpdate;
use Ankh\GroupUpdate;
use Ankh\PageUpdate;

use Jenssegers\Date\Date;

class UpdateRepositoryEloquent extends EntityRepositoryEloquent implements UpdateRepositoryContract {

	protected $entity;

	public function __construct(\Ankh\Update $model) {
		$this->setModel($model);
	}

	public function setEntity(\Ankh\Entity $entity) {
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

	private $authors = [];

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
		if ($class == \Ankh\Entity::class) {
			foreach ($this->known() as $model) {
				if ($model->updateType() == $r_type)
					return $model->newFromBuilder($update->getAttributes());
			}

			// throw new \Exception("Unknown update type [$r_type] for entity class [$class]");
		}

		return $update;
	}

	public function daily() {
		$grouped = [];
		$idx = 0;
		foreach ($this as $update) {
			$date = Date::instance($update->created_at);

			$grouped[$date->format('Y-m-d 00:00:00')][] = $this->transform($update);
		}
		krsort($grouped);

		return $grouped;
	}

	public function authorly(array $group) {
		$r = [];
		$idx = 0;
		foreach ($group as $date => $update) {
			$author = $update->relatedAuthor();
			if ($author) {
				$this->authors[$author->id] = $author;
				$r[$author->fio . ' ' . $author->id][] = $update;
			} else {
				$this->authors[0] = new \Ankh\Author(['fio' => 'Unknown author']);
				$r['error'][] = $update;
			}

		}
		krsort($r);
		return $r;
	}

	public function dateOrigin($origin) {
		return Date::createFromFormat('Y-m-d H:i:s', $origin);
	}

	public function dateDaysDiff($date) {
		$now = Date::now();
		$now->hour = 0;
		$now->minute = 0;
		$now->second = 0;
		return $date->diff($now)->days;
	}

	public function authorOrigin($origin) {
		preg_match('/(\d+)$/', $origin, $m);
		return @$this->authors[intval($m[1])];
	}

}
