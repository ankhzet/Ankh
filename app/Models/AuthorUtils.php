<?php namespace Ankh;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use Ankh\Parsing\Parser;
use Ankh\Parsing\Fetcher;

use Ankh\Synker\GroupSynker;
use Ankh\Synker\PageSynker;

class AuthorUtils {

	public function check(Entity $entity) {
		$link = $entity->absoluteLink();
		$params = [];

		$fetcher = new Fetcher;
		$data = $fetcher->pull($link, $params);

		if ($data === false || trim($data) == '')
			return false;

		$class = ucfirst(class_basename($entity));

		$parser = new Parser;
		if (method_exists($parser, $method = "parse{$class}")) {
			$data = $parser->{$method}($data);
			if (method_exists($this, $method = 'check' . $class)) {
				return $this->{$method}($entity, $data);
			} else
				return $data;
		}

		throw new \Exception('Doesn\'t know, how to parse' . $class . ' html');
	}


	public function checkAuthor(Author $author, array $data) {
		$stats = [];

		$author->fio = $data['fio'];

		$author->info = json_encode(array_except($data, ['fio', 'groups']), JSON_UNESCAPED_UNICODE);

		$authors = $author->diffAttributes();

		$author->save();

		if ($groups = $this->synkGroups($author, $data['groups']))
			$authors['groups'] = $groups;

		if ($authors)
			$stats['authors'][] = $authors;

		return $stats;
	}

	function synkGroups(Author $author, array $groupsData) {
		$stats = with($gs = new GroupSynker($author))->synk($groupsData);

		$groups = $gs->current();

		$pages = [];
		foreach ($groups as $group) {
			$data = GroupSynker::pick($group, $groupsData);

			if (!$group->inline)
				$pagesData = $data['pages'];
			else
				$pagesData = $this->check($group);

			$pages = array_merge($pages, array_map(function (&$value) use (&$data) {
				$value['group'] = $data;
				return $value;
			}, $pagesData));
		}

		if ($pages)
			if ($pages = with(new PageSynker($group->author))->synk($pages))
				$stats['pages'] = $pages;

		return $stats;
	}

}


