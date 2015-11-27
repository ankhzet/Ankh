<?php namespace Ankh;

use User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Ankh\Contracts\Entity as EntityContract;

use Ankh\Traits\Entity\DateAccessorTrait;
use Ankh\Traits\Entity\LayeredRepositoryTrait;
use Ankh\Traits\Entity\FilterableTrait;
use Ankh\Traits\Entity\OrderableTrait;
use Ankh\Traits\Entity\CollumnLetterTrait;

class Entity extends Model implements EntityContract {
	use SoftDeletes;

	use DateAccessorTrait;

	use LayeredRepositoryTrait;

	use FilterableTrait;
	use OrderableTrait;

	use CollumnLetterTrait;


	public function __call($method, $args) {
		preg_match('"([a-z]+)[A-Z$]"', $method, $match);
		$prefix = @$match[1];
		if (($prefix == 'pick') || ($prefix == 'paginate')) {
			$picker = $this->pick(substr($method, strlen($prefix)), @$args[0] ?: 10);
			if ($prefix == 'paginate')
				$picker = $picker->paginate();
			return $picker;
		}

		return parent::__call($method, $args);
	}

	public function pick($from, $amount = 10) {
		return new Picker($this, $from, $amount);
	}

}

class Picker {

	protected $from;
	protected $amount;

	protected $trashed = false;
	protected $paginate = false;

	protected $orderBy;
	protected $orderDir;

	protected $query;

	public function __construct(Entity $entity, $from, $amount) {
		$this->from = $from;
		$this->amount = $amount;

		try {
			$this->query = $entity->{$from}();
		} catch (Exception $e) {
			throw new Exception("Unknown relation [{$from}]");
		}
	}

	public function allTrashed(&$delta = null) {
		return $this->trashed(User::isUserAdmin())->all($delta);
	}

	public function all(&$delta = null) {
		$delta = 0;

		$query = $this->query;

		if ($this->trashed)
			$query->withTrashed();

		if ($this->paginate) {
			if ($this->trashed)
				$query = $query->orderBy('deleted_at', 'asc');

			if ($this->orderBy)
				$query = $query->orderBy($this->orderBy, $this->orderDir);

			$query = $query->paginate($this->amount);
		} else {
			$delta = max(0, $query->count() - $this->amount);

			if ($this->trashed)
				$query = $query->orderBy('deleted_at', 'asc');

			$paginator = $query->take($this->amount);

			$query = $paginator->orderBy('updated_at', 'desc')->get();
		}

		return $query;
	}

	public function orderBy($column, $direction = 'desc') {
		$this->orderBy = $column;
		$this->orderDir= $direction;
		return $this;
	}

	public function paginate($paginate = true) {
		$this->paginate = $paginate;
		return $this;
	}

	public function trashed($trashed = true) {
		$this->trashed = $trashed;
		return $this;
	}



}
