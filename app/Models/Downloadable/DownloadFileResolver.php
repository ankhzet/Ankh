<?php namespace Ankh\Downloadable;

use Closure;

use Ankh\Page;
use Ankh\ResourceResolver;

class DownloadFileResolver extends ResourceResolver {

	public function __construct() {
		$this->transforms = join([
			$this->checkablePartial('format'),
			$this->checkablePartial('encoding'),
			$this->checkablePartial('compression'),
		]);

		$this->title = '{:author} - {:page-title}';
		$title = $this->checkable('title', function (DownloadFileResolver $self) {
			return rtrim($self->resolve('{:title}'), '.');
		});

		$extension = $this->checkable('extension', function (DownloadFileResolver $self) {
			return ifelse($self->resolve('{:extension}'), function ($extension) {
				return '.' . trim($extension, '.');
			});
		});

		$this->setPattern(join(['{:transforms}', $title, $extension]));
	}

	public function setPage(Page $page) {
		$this->page = $page;
		$this->{'page-title'} = $page->title;
		$this->author = $page->author->fio;
		return $this;
	}

	function checkable($fragment, Closure $callback) {
		$check = "check" . ucfirst($fragment);
		$this->setFragment($check, $callback);
		return "{:$check}";
	}

	function checkablePartial($fragment, $format = "{:replace}/") {
		return $this->checkable($fragment, $this->partial($fragment, $format));
	}

	function partial($partial, $format = "{:replace}") {
		return function (DownloadFileResolver $self) use ($partial, $format) {
			return ifelse($self->resolve("{:$partial}"), function ($part) use ($format) {
				return str_replace('{:replace}', $part, $format);
			});
		};
	}

}

function ifelse($value, Closure $callback, Closure $else = null) {
	if ($value)
		$value = $callback($value);

	return $value ?: ($else ? $else() : null);
}
