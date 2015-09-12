<?php namespace Ankh\Contracts\Downloadable;

interface Downloadable extends Transformable {

	public function filename();
	public function size();
	public function getContents();

}
