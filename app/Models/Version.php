<?php namespace Ankh;

class Version {

	protected $entity;
	protected $timestamp;

	public function setEntity($entity) {
		$this->entity = $entity;
	}

	public function entity() {
		return $this->entity;
	}

	public function timestamp() {
		return $this->timestamp;
	}
}
