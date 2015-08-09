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

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function encode() {
		return \Carbon\Carbon::createFromTimestamp($this->timestamp)->format('d-m-Y/H-i-s');
	}

	public function __toString() {
		return $this->encode();
	}

}
