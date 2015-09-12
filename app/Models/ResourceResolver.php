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
		$fragment = @$this->fragments[$name];
		if ($fragment)
			if (is_callable($fragment))
				$fragment = $fragment($this);
			else
				if (strpos($fragment, '{:') !== false)
					$fragment = $this->resolve($fragment);

		return $fragment;
	}

	public function resolve($fragment = null) {
		$fragment = $fragment ?: $this->pattern;

		$fragments = [];

		$result = preg_replace_callback('/\{:([^\}]+)\}/i', function ($matches) use (&$fragments) {
			$fragment = (string)$matches[1];

			return $fragments[$fragment] = $this->fragment($fragment);
		}, $fragment);

		if (count($fragments) && empty(array_filter($fragments)))
			return null;

		return $result;
	}

	public function __get($fragment) {
		return $this->fragment($fragment);
	}

	public function __set($fragment, $value) {
		return $this->setFragment($fragment, $value);
	}

}

