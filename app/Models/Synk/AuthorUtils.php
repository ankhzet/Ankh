<?php namespace Ankh\Synk;

use Ankh\Synk\Parsing\Parser;

use Ankh\Entity;
use Ankh\Author;

use Ankh\Contracts\Synk\Fetch;

class AuthorUtils {

	public function check(Entity $entity) {
		$link = $entity->absoluteLink();

		$fetch = app(Fetch::class);
		$data = $fetch->pull($link);

		if (!$fetch->isOk())
			throw new CheckError($entity, $fetch->code());

		$data = $this->fixHTML($data);

		$class = ucfirst(class_basename($entity));

		$parser = new Parser;
		if (method_exists($parser, $method = "parse{$class}")) {
			$data = $parser->{$method}($data);

			if (method_exists($this, $method = 'check' . $class)) {
				return $this->{$method}($entity, $data);
			} else
				return $data;
		}

		throw new CheckError($entity, 'Doesn\'t know, how to parse supplied html');
	}

	function fixHTML($html) {
		$html = str_replace('</small><p><font', '</dl></j><j><dl><p><font', $html);
		$html = strip_unwanted_tags($html, ['div']);
		return $html;
	}


	public function checkAuthor(Author $author, array $data) {
		$stats = [];

		$author->fio = array_pull($data, 'fio');
		$author->title = array_pull($data, 'title');
		$author->rating = array_pull($data, 'rating');
		$author->visitors = array_pull($data, 'visitors');

		$groups = array_pull($data, 'groups');

		$authors = $author->diffAttributes();

		$groups = $this->synkGroups($author, $groups);
		if ($groups) {
			$authors['groups'] = $groups;
			$authors['id'] = $author->id;
		}

		if ($authors)
			$author->touch();

		$author->save();

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

			if (!is_array($pagesData)) {
				debug($pagesData, $group->inline);
				continue;
			}

			$data = array_map(function (&$value) use (&$data) {
				$value['group'] = $data;
				return $value;
			}, $pagesData);

			$pages = array_merge($pages, $data);
		}

		if ($pages)
			if ($pages = with(new PageSynker($group->author))->synk($pages)) {
				$stats['pages'] = $pages;
				$stats['id'] = $group->id;
			}

		return $stats;
	}

}


