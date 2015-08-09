<?php namespace Ankh;

class TidyCleaner extends BasicSamlibHtmlCleaner {

	public function __construct() {
		$this->setOptions(array(
			'output-xhtml' => true,
			'indent' => false,
			'wrap' => 0,
			'tidy-mark' => false,
			'new-pre-tags' => 'xxx7',
			));
	}

	public function cleanHtml($html, $encoding = 'utf8') {
		$tidy = tidy_parse_string($html, $this->options(), $encoding = 'utf8');
		$tidy->cleanRepair();
		$html = join('', $tidy->body()->child ?: []);
		$html = str_replace(PHP_EOL, '', $html);
		return $html;
	}

}
