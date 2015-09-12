<?php namespace Ankh\Traits\Downloadable;

trait TransformableTrait {

	protected $contents;
	protected $type;

	public function size() {
		return strlen($this->getContents());
	}

	public function getContents() {
		return $this->contents;
	}

	public function setContents($data) {
		$this->contents = $data;
		return $this;
	}

	public function setType($type) {
		$this->type = $type;
	}

	function __toString() {
		return (string) $this->getContents();
	}

}

