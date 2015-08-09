<?php namespace Ankh\Contracts;

interface HtmlCleaner {

	public function clean($html, $encoding = 'utf8');

	public function options();
	public function setOptions(array $options);
	public function setOption($option, $value);

}

