<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformable;
use Ankh\Contracts\Downloadable\Downloadable;
use Ankh\Contracts\Downloadable\Zipable;

use Ankh\Version;

use Ankh\Traits\Downloadable\TransformableTrait;

class FileVersion extends DownloadFileResolver implements Downloadable, Zipable {

	use TransformableTrait;

	public function setVersion(Version $version) {
		$this->version = $version;
		$this->extension = 'html';
		return $this;
	}

	public function getContents() {
		if (!$this->contents)
			$this->contents = $this->version->contents();

		return $this->contents;
	}

	public function setType($type) {
		$this->extension = $type;
	}

	public function give() {
		return $this->getContents();
	}


	public function path() {
		return $this->resolve();
	}

	public function datetime() {
		return $this->version->time();
	}

	public function data() {
		return $this->getContents();
	}

	public function comment() {
		$page = $this->version->entity();

		$title = $this->title;
		$link = 'http://samlib.ru/' . ltrim($page->absoluteLink(), '/');
		$size = file_size($this->size());
		$date = $this->version->encode('d/m/Y H:i:s');
		$annotation = (string)(app('plaintexter')->apply(new TransformableContainer($page->annotation)));
		$annotation = wordwrap($annotation, 120);

		return "{$title} ($size)\n{$date}\n\n{$link}\n\n{$annotation}";
	}

	public function filename() {
		return basename($this->path());
	}

}

class TransformableContainer implements Transformable {

	use TransformableTrait;

	function __construct($contents) {
		$this->contents = $contents;
	}

}
