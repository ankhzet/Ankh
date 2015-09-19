<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformation;
use Ankh\Contracts\Downloadable\Transformable;

class Plaintexter implements Transformation {

	const EXTENSION = 'txt';

	const LINEBREAK = "\r\n";

	public function apply(Transformable $transformable) {
		$data = str_ireplace(["\r", "\n"], '', $transformable->getContents());

		$data = preg_replace("~<(/?(br|p|dd))[^>]*?>~i", '<\1>', $data);

		$data = preg_replace("~</(p|dd)>~i", '', $data);

		$data = preg_replace("~<(br|p|dd)>~i", static::LINEBREAK, $data);

		$data = str_ireplace(['&nbsp;', '&nbsp'], ' ', $data);

		$data = strip_tags($data);

		$trans = get_html_translation_table(HTML_ENTITIES);
		$trans = array_flip($trans);
		$data = strtr($data, $trans);

		$data = strip_tags($data);

		$data = preg_replace('/ {3,}/', '  ', $data);

		$data = preg_replace('/' . static::LINEBREAK . '[ ]+/', static::LINEBREAK . "\t", $data);

		$transformable->setContents(rtrim($data, static::LINEBREAK) . static::LINEBREAK);

		$transformable->setType(static::EXTENSION);

		return $transformable;
	}

}
