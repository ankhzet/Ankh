<?php namespace Ankh;

class ResourceResolver {

	private $fragments = [];
	private $pattern;

	public function pattern() {
		return $this->pattern;
	}

	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}

	public function setFragment($name, $fragment) {
		$this->fragments[$name] = $fragment;
		return $this;
	}

	public function fragment($name) {
		return $this->fragments[$name];
	}

	public function resolve() {
		return preg_replace_callback('/\{:([^\}]+)\}/i', function ($matches) {
			return $this->fragment((string)$matches[1]);
		}, $this->pattern);
	}

}

