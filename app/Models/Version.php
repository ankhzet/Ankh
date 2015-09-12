<?php namespace Ankh;

use Carbon\Carbon;
use Ecxeption;

use Ankh\Contracts\Resolvable;
use PageUtils;

class Version implements Resolvable {

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

	public function time() {
		return $this->time;
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

	public function resolver() {
		return $this->entity()->resolver()->setVersion($this);
	}

	public function contents() {
		return PageUtils::contents($this->resolver());
	}

	public function setContents($data) {
		return PageUtils::putContents($this->resolver(), $data);
	}

}
