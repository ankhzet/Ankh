<?php namespace Ankh;

class PageResolver extends ResourceResolver {

	public function __construct() {
		$this->setPattern('{:id}/{:timestamp}.html');
		$this->setVersion();
	}

	public function setPage(Page $page) {
		$this->id = $page->id;
		return $this;
	}

	public function setVersion(Version $version = null) {
		$this->timestamp = $version ? $version->timestamp() : 'last';
		return $this;
	}

}
