<?php namespace Ankh\Admin;

use Storage;

use Ankh\Contracts\Downloadable\Downloadable;
use Ankh\Contracts\Downloadable\Zipable;
use Ankh\Traits\Downloadable\TransformableTrait;

class LogFile implements Downloadable, Zipable {

	use TransformableTrait;

	var $filename;

	function __construct($filename) {
		$this->filename = $filename;
		$this->setType(pathinfo($filename, PATHINFO_EXTENSION));

		if (Storage::disk('logs')->exists($this->filename))
			$this->setContents(Storage::disk('logs')->get($this->filename));
		else
			$this->setContents(null);
	}

	public function filename() {
		return pathinfo(basename($this->filename), PATHINFO_FILENAME) . '.' . $this->extension;
	}

	public function path() {
		return $this->filename();
	}

	public function datetime() {
		return Carbon::createFromTimestamp(Storage::disk('logs')->lastModified($this->filename));
	}

	public function data() {
		return $this->getContents();
	}

	public function comment() {
		return 'Laravel application log file';
	}

	public function delete() {
		return Storage::disk('logs')->put($this->filename, "");
	}


}

