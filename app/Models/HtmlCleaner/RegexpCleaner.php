<?php namespace Ankh;

class RegexpCleaner extends BasicSamlibHtmlCleaner {

	public function cleanHtml($html, $encoding = 'utf8') {
		$html = preg_replace('"<([^\/[:alpha:]])"i', '&lt;\1', $html);

		$html = str_replace('  ', ' ', $html);
		$html = str_replace('  ', ' ', $html);

		$html = preg_replace('"<p([^>]*)?>(.*?)<dd>"i', '<p\1>\2<dd>', $html);
		$html = preg_replace('"(</?(td|tr|table)[^>]*>)'.PHP_EOL.'"', '\1', $html);
		$html = preg_replace('"'.PHP_EOL.'(</?(td|tr|table)[^>]*>)"', '\1', $html);
		$html = str_replace(array(PHP_EOL, "\r", "\n", '</dd>'), '', $html);
		$html = str_replace(array('<dd>', '<br>', '<br/>', '<br />', '<p>'), PHP_EOL, $html);
		$html = preg_replace('"<p\s*>([^<]*)</p>"i', '<p>\1', $html);
		$html = preg_replace('/'.PHP_EOL.'{3,}/', PHP_EOL.PHP_EOL, $html);
		$html = preg_replace('"<(\w+)[^>]*>((\s|\&nbsp;)*)</\1>"', '\2', $html);
		$html = preg_replace('"</(\w+)>([^'.PHP_EOL.']*)<\1>"i', '\2', $html);
		$html = preg_replace('"<font([^<]*)color=\"?black\"?([^<]*)>"i', '<font\1\2>', $html);
		$html = preg_replace('"<(font|span)\s*(lang=\"?[^\"]+\"?)\s*>([^<]*)</\1>"i', '\3', $html);
		$html = preg_replace('"<font\s*>(?>((?>(?!</?font).)+)|(?R))*</font>"sxi', '\1', $html);
		$html = str_replace('</p>', '', $html);
		/** /				$html = preg_replace('"<p\s*>(?>((?>(?!</?p).)+)|(?R))*</p>"sxi', '<p>\1', $html);/**/
//				$html = preg_replace('"</(b|i)><\1>"i', '', $html);
		$html = str_replace(array('</b><b>','</i><i>','</B><B>','</I><I>'), '', $html);
		$html = preg_replace('"([^ ])&nbsp;([^ ])"', '\1 \2', $html);
		$html = str_replace('&nbsp;', ' ', $html);
		$html = str_replace('&nbsp', ' ', $html);
		$html = str_replace("\t", '  ', $html);
		$html = preg_replace('/ {3,}/', '  ', $html);
		$html = preg_replace('/ {2,}/', '    ', $html);

		$html = preg_replace('"(<div[^>]*>(?![^\r\n]+</div>)[^\r\n]+)[\r\n]"i', '\1</div>', $html);
		return $html;
	}

}
