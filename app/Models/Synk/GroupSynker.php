<?php namespace Ankh\Synk;

use Illuminate\Support\Str;

use Ankh\Entity;
use Ankh\Author;
use Ankh\Group;

class GroupSynker extends Synker {

	public function __construct(Author $author) {
		parent::__construct($author, 'groups');
	}

	function dataToEntityData(array $data) {
		$data = array_except($data, ['idx', 'pages']);

		$data['inline'] = $inline = (trim($link = $data['link'], '/') != '') && !Str::startsWith($link, '/type/');
		if (!$inline)
			$data['link'] = null;

		return $data;
	}

	public static function same(Entity $group, array $data, $strict = true) {
		if (Str::equals($group->title, $data['title']))
			return true;

		return $strict ? false :
			 ($group->link && Str::equals($group->link, $data['link']))
		|| ($group->annotation && Str::equals($group->annotation, $data['annotation']))
		;
	}

	protected function createEntity(array $data) {
		return new Group($data);
	}

}

