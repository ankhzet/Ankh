<?php namespace Ankh\Contracts\Synk;

interface Fetch {

	public function pull($resource);

	public function isOk();

	public function resource($resource = null);

	public function code($code = null);

	public function response($response = null);

	public function data($data = null);

	public function time();

}
