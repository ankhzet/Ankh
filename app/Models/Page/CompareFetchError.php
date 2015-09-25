<?php namespace Ankh\Page;

use Exception;
use Ankh\Entity;

class CompareFetchError extends Exception {
	protected $model;
	protected $error;

	function __construct(Entity $entity, $error = 0, \Exception $prev = null) {
		$this->model = $entity;
		$this->error = $error;

		parent::__construct((string) $this, @intval($error), $prev);
	}

	public function __toString() {
		$class = ucfirst(class_basename($this->model));
		$error = (string)($this->error);
		return class_basename($this) . ": [{$this->code}] "
		. "Failed to comapre $class ['id': {$this->model->id}] as remote version fetch failed\n$error";
	}

}

