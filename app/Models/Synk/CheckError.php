<?php namespace Ankh\Synk;

use Exception;
use Ankh\Entity;

class CheckError extends Exception {
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
		. "Failed to check $class ['id': {$this->model->id}]\n$error";
	}

}

