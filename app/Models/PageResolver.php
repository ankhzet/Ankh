<?php namespace Ankh;

class PageResolver extends ResourceResolver {

	public function __construct() {
		$this->setPattern('cache/page/{:id}/{:version}.html');
		$this->setVersion();
	}

	public function setPage(Page $page) {
		return $this->setFragment('id', $page->id);
	}

	public function setVersion(Version $version = null) {
		return $this->setFragment('version', $version ? $version->timestamp() : 'last');
	}

}
