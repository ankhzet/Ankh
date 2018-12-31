<?php namespace Ankh;

class BasicSamlibHtmlCleaner extends BasicHtmlCleaner {

	const SAMLIB_DOMAIN = 'http://samlib.ru/';

	public function clean($html, $encoding = 'utf8') {
		$html = $this->cleanHtml($html);
		$html = $this->wrapExternalImages($html);
		$html = $this->resolveImages($html);
		return $html;
	}

	public function resolveImages($html) {
		return preg_replace_callback('|<img ([^>]*?)(src=(["\']?))/([^>\3]+\3[^>]*)>|i', function ($matches) {
			return '<img ' . $matches[1] . $matches[2] . self::SAMLIB_DOMAIN . $matches[4] . ' \>';
		}, $html);
	}

	public function wrapExternalImages($html) {
		return preg_replace_callback('#(^|\s)(https?://.*?\.(jpe?g|png|gif|bmp))#iu', function ($matches) {
			$leading = $matches[1];
			$src = $matches[2];

			return "$leading<a class=\"inline-preview\" href=\"$src\" target=\"_blank\"><img src=\"$src\" title=\"$src\" alt=\"$src\"/></a>";
		}, $html);
	}

	public function cleanHtml($html, $encoding = 'utf8') {
		return $html;
	}

}
