<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformation;
use Ankh\Contracts\Downloadable\Transformable;

class Htmlizer implements Transformation {

	const EXTENSION = 'html';

	const LINEBREAK = "\r\n";

	public function apply(Transformable $transformable) {

		$file = \File::get(public_path('assets/img/borders.png'));
		$img = base64_encode($file);

		$data = str_ireplace(["\r", "\n"], '', $transformable->getContents());

		$data = preg_replace("~<(/?(br|p|dd))[^>]*?>~i", '<\1>', $data);

		$data = preg_replace("~</(p|dd)>~i", '', $data);

		$data = preg_replace("~<(br|p|dd)>~i", static::LINEBREAK, $data);

		$data = preg_replace('/[ ]{2,}/', ' ', $data);

		$data = preg_replace("/" . static::LINEBREAK . "[ ]+/s", static::LINEBREAK . "    ", $data);

		$data = str_replace(static::LINEBREAK, '<p>', $data);

		$transformable->setContents($data);

		$charset = $transformable->charset ?: 'utf-8';
		$view = view('pages.html-download', compact('transformable', 'img'));

		$transformable->setContents((string)$view);

		$transformable->setType(static::EXTENSION);

		return $transformable;
	}

}
