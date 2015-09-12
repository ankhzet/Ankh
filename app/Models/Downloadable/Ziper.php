<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformation;
use Ankh\Contracts\Downloadable\Transformable;

class Ziper implements Transformation {

	public function apply(Transformable $transformable) {
		return $transformable->setContents((new Zip())->singleFile($transformable));
	}


}
