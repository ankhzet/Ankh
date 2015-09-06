<?php namespace Ankh\Contracts\Synk;

interface Fetcher {

	public function fetch(Fetch $fetch);

	public function isOk(Fetch $fetch);

}
