<?php namespace Ankh;

class BasicSamlibHtmlCleaner extends BasicHtmlCleaner {

	const SAMLIB_DOMEN = 'http://samlib.ru/';

	public function clean($html, $encoding = 'utf8') {
		$html = $this->cleanHtml($html);
		$html = $this->resolveImages($html);
		return $html;
	}

	public function resolveImages($html) {
		return preg_replace_callback('|<img ([^>]*?)(src=(["\']?))/([^>\3]+\3[^>]*)>|i', function ($matches) {
			return '<img ' . $matches[1] . $matches[2] . self::SAMLIB_DOMEN . $matches[4] . ' \>';
		}, $html);
	}

	public function cleanHtml($html, $encoding = 'utf8') {
		return $html;
	}

}
