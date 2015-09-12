<?php namespace Ankh\Contracts\Downloadable;

interface Transformation {

	public function apply(Transformable $transformable);

}
