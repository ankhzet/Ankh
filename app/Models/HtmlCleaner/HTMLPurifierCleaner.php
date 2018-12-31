<?php namespace Ankh;

use HTMLPurifier, HTMLPurifier_Config;

class HTMLPurifierCleaner extends BasicSamlibHtmlCleaner {

	public function __construct() {
		$this->setOptions(array(
			'Core.Encoding' => 'UTF-8',
			'HTML.Doctype' => 'XHTML 1.0 Transitional',
			'HTML.Allowed' => 'dd,table[summary],tr,td[abbr],thead,tbody,div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
			'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
			// 'Core.CollectErrors' => true,
			// 'AutoFormat.AutoParagraph' => true,
			// 'AutoFormat.RemoveEmpty' => true
			));
	}

	public function cleanHtml($html, $encoding = 'utf8') {
		require base_path('vendor/ezyang/htmlpurifier/library/') . 'HTMLPurifier.auto.php';

		$html = str_replace(["\r", '<dd>'], '', $html);
		$html = preg_replace("'\n\s*\n's", "\n", $html);
		$html = str_replace("\n", '<br>', $html);

		$config = HTMLPurifier_Config::createDefault();
		$config->loadArray($this->options());

		$purifier = new HTMLPurifier($config);
		$html = $purifier->purify($html);

		$html = preg_replace('#<br[^>]*>#', chr(1), $html);
		$html = str_replace(html_entity_decode('&nbsp;'), ' ', $html);
		$html = str_replace("\t", " ", $html);
		$html = preg_replace("# *" . chr(1) . " ++#m", chr(1), $html);
		$html = str_replace(chr(1), "\n\u{2003}\u{2003}", $html);

		$html = rtrim($html);

		return $html;
	}

}
