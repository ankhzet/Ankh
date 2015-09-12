<?php namespace Ankh\Contracts\Downloadable;

interface Transformable {

	public function getContents();

	public function setContents($data);

	public function setType($type);

}
