<?php namespace Ankh;

use HTMLPurifier, HTMLPurifier_Config;

class HTMLPurifierCleaner extends BasicSamlibHtmlCleaner {

	public function __construct() {
		$this->setOptions(array(
			'Core.Encoding' => 'UTF-8',
			'HTML.Doctype' => 'XHTML 1.0 Transitional',
			'HTML.Allowed' => 'dd,table[summary],tr,td[abbr],thead,tbody,div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
			'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
			// 'AutoFormat.AutoParagraph' => true,
			// 'AutoFormat.RemoveEmpty' => true
			));
	}

	public function cleanHtml($html, $encoding = 'utf8') {
		require base_path('vendor/ezyang/htmlpurifier/library/') . 'HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		$config->loadArray($this->options());

		$html = str_replace("\r", '', $html);
		$html = preg_replace("'\n\s*\n's", "\n", $html);
		$html = str_replace("\n", '<br />', $html);

		$purifier = new HTMLPurifier($config);
		$html = $purifier->purify($html);

		$html = str_replace("\n", '', $html);
		$html = str_replace(html_entity_decode('&nbsp;'), ' ', $html);
		$html = preg_replace('"\s{2,}"', "&nbsp; &nbsp; ", $html);
		$html = str_replace('<br />', '<dd>', $html);

		return $html;
	}

}
