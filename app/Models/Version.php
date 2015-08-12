<?php namespace Ankh;

class Version {

	protected $entity;
	protected $time;

	public function setEntity($entity) {
		$this->entity = $entity;
	}

	public function entity() {
		return $this->entity;
	}

	public function timestamp() {
		return $this->time ? $this->time->timestamp : 0;
	}

	public function setTimestamp($timestamp) {
		if (is_object($timestamp))
			$this->time = $timestamp;
		else
			$this->time = \Carbon\Carbon::createFromTimestamp($timestamp);
	}

	public function encode() {
		return $this->time->format('d-m-Y+H-i-s');
	}

	public function __toString() {
		return $this->encode();
	}

}
