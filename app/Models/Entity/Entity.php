<?php namespace Ankh;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Ankh\Contracts\Entity as EntityContract;

class Entity extends Model implements EntityContract {
	use SoftDeletes;

	use \Ankh\Traits\Entity\DateAccessorTrait;

	use \Ankh\Traits\Entity\LayeredRepositoryTrait;

	use \Ankh\Traits\Entity\FilterableTrait;
	use \Ankh\Traits\Entity\OrderableTrait;

	use \Ankh\Traits\Entity\CollumnLetterTrait;

}
