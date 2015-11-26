<?php namespace Ankh;

class PageResolver extends ResourceResolver {

	public function __construct() {
		$this->setPattern('{:id}/{:timestamp}.html');
		$this->setVersion();
	}

	public function setPage(Page $page) {
		$this->page = $page;
		$this->id = $page->id;
		return $this;
	}

	public function setVersion(Version $version = null) {
		$timestamp = $version ? $version->timestamp() : 0;
		$this->timestamp = $timestamp ?: 'last';
		return $this;
	}

	public function last() {
		return with(clone $this)->setVersion(null);
	}

}
