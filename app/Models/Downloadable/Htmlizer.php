<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformation;
use Ankh\Contracts\Downloadable\Transformable;

class Htmlizer implements Transformation {

	const EXTENSION = 'html';

	const LINEBREAK = "\r\n";

	public function apply(Transformable $transformable) {

		$file = \File::get(public_path('assets/img/borders.png'));
		$img = base64_encode($file);

		$data = $transformable->getContents();

		$data = preg_replace("~<(/?(br|p|dd))[^>]*?>~i", '<\1>', $data);

		$data = preg_replace("~</(p|dd)>~i", '', $data);

		$data = preg_replace("~<(br|p|dd)>~i", static::LINEBREAK, $data);

		$data = preg_replace('/[ ]{2,}/', ' ', $data);

		$data = preg_replace("/" . static::LINEBREAK . "[ ]+/s", static::LINEBREAK . "    ", $data);

		$data = str_replace(static::LINEBREAK, '<p>', $data);

		$page = $transformable->page;
		$author = $page->author;

		$charset = $transformable->charset ?: 'utf-8';
		$title = $author->fio . " - " . $page->title;
		$link = \HTML::link(path_join("http://samlib.ru", $author->absoluteLink()), $author->fio) . " - "
		 . \HTML::link(path_join("http://samlib.ru", $page->absoluteLink()), $page->title);

		$annotation = $page->annotation;
		$contents = $data;

		$downloaded = \Lang::get('pages.pages.downloaded', ['url' => \Request::fullUrl()]);

		if ($charset != 'utf-8') {
			$e = app('charset-encoder');
			$c = $e->remap($charset, true);

			$title = $e->transform($title, 'utf-8', $c);
			$link = $e->transform($link, 'utf-8', $c);
			$annotation = $e->transform($annotation, 'utf-8', $c);
			$downloaded = $e->transform($downloaded, 'utf-8', $c);
		}

		$view = view('pages.html-download', compact('img', 'charset', 'title', 'link', 'annotation', 'contents', 'downloaded'));

		$transformable->setContents((string)$view);

		$transformable->setType(static::EXTENSION);

		return $transformable;
	}

}
