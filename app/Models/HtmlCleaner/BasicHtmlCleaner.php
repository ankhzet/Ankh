<?php namespace Ankh;

use Ankh\Contracts\HtmlCleaner as HtmlCleanerContract;

class BasicHtmlCleaner implements HtmlCleanerContract {

	protected $options = [];

	public function options() {
		return $this->options;
	}

	public function setOptions(array $options) {
		$this->options = $options;
	}

	public function setOption($option, $value) {
		$this->options[$option] = $value;
	}

	public function clean($html, $encoding = 'utf8') {
		return $html;
	}

}
