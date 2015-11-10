<?php namespace Ankh\Synk;

use Illuminate\Support\Str;

use Ankh\Entity;
use Ankh\Author;
use Ankh\Page;

class PageSynker extends Synker {
	protected $groups;

	public function __construct(Author $author) {
		parent::__construct($author, 'pages');
	}

	function groups() {
		if (!$this->groups)
			$this->groups = $this->parent->groups()->get();

		return $this->groups;
	}

	protected function dataToEntityData(array $data) {
		return array_except($data, ['rating', 'images']);
	}

	public static function same(Entity $page, array $data, $strict = true) {
		if (Str::equals($page->link, $data['link']))
			return true;

		return $strict ? false :
			Str::equals($page->title, $data['title'])
			|| ($page->annotation && Str::equals($page->annotation, @$data['annotation']))
		;
	}

	protected function createEntity(array $data) {
		$entity = new Page($data);

		$group = GroupSynker::select($this->groups(), $data['group']);

		if (!$group)
			throw new \Exception('Failed to bind group: no matched group found: ' . $this->groupData['title']);

		$entity->group()->associate($group);
		return $entity;
	}

}
