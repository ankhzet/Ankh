<?php namespace Ankh;

use Carbon\Carbon;
use Ecxeption;

class Version {

	protected $entity;
	protected $time;

	public function __construct($timestamp = null) {
		if ($timestamp)
			$this->setTimestamp($timestamp);
	}

	public function setEntity(Resolvable $entity) {
		$this->entity = $entity;
		return $this;
	}

	public function entity() {
		return $this->entity;
	}

	public function timestamp() {
		return $this->time ? $this->time->timestamp : 0;
	}

	public function setTimestamp($timestamp) {
		switch (true) {
		case is_object($timestamp):
			$this->time = $timestamp;
			break;
		case is_string($timestamp):
			$this->time = Carbon::createFromFormat('d-m-Y\+H-i-s', $timestamp);
			break;
		case is_numeric($timestamp):
			$this->time = Carbon::createFromTimestamp($timestamp);
			break;
		default:
			$timestamp = e($timestamp);
			throw new Exception("Don't know how to interpret [{$timestamp}] version identifier");
		}

		return $this;
	}

	public function encode($format = 'd-m-Y+H-i-s') {
		return $this->time->format($format);
	}

	public function __toString() {
		return $this->encode();
	}

}
