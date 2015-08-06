<?php namespace Ankh;

use Illuminate\Database\Eloquent\Model;

use Ankh\Contracts\Entity as EntityContract;

class Entity extends Model implements EntityContract {

	use \Ankh\Traits\Entity\DateAccessorTrait;

	use \Ankh\Traits\Entity\LayeredRepositoryTrait;

	use \Ankh\Traits\Entity\FilterableTrait;
	use \Ankh\Traits\Entity\OrderableTrait;

	use \Ankh\Traits\Entity\CollumnLetterTrait;

}
