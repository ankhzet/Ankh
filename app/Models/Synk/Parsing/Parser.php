<?php namespace Ankh\Synk\Parsing;

use Symfony\Component\DomCrawler\Crawler;

function wrap($node, callable $wrapper = null) {
	if ($node instanceof Crawler) {
		if (!count($node))
			return [];

		return $node->each(function (Crawler $node) use ($wrapper) {
			return $wrapper ? $wrapper($node) : $node;
		});

	}

	if ($wrapper) {
		$r = [];
		foreach ($node as $s)
			$r[] = $wrapper($s);

		$node = $r;
	}

	return $node;
}

function firstNode($nodes, callable $filter) {
	return array_first(wrap($nodes), function ($i, Crawler $node) use ($filter) {
		return $filter($node);
	});
}

function firstTag($nodes, $tag) {
	$tag = strtolower($tag);
	return firstNode($nodes, function (Crawler $node) use ($tag) {
		return strtolower($node->nodeName()) == $tag;
	});
}

function tag($nodes, $tag) {
	if (!is_array($tag))
		$tag = [$tag];

	foreach ($tag as &$t)
		$t = strtolower($t);

	$r = [];
	foreach (wrap($nodes) as $node)
		if (str_contains(strtolower($node->nodeName()), $tag))
			$r[] = $node;

	return $r;
}

class Parser {

	public function parseAuthor($html) {
		$html = mb_convert_encoding($html, 'UTF-8', 'CP1251');
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);

		$infoTbl = $crawler->filter('table')->first();

		$info = $this->authorInfo($infoTbl);

		$font = firstTag($infoTbl->nextAll(), 'font');
		$i = firstTag($font->children(), 'i');

		$about = trim($i ? $i->html() : null);
		$groups = $this->authorGroups(wrap($crawler->filter('j'), function ($node) {
			return tag($node->children(), 'dl');
		}));

		$info = array_merge($info, $this->authorCredentials($crawler));
		$info = array_merge($info, ['about' => $about]);
		$info = array_merge($info, ['groups' => $groups]);

		return $info;
	}

	function authorCredentials(Crawler $crawler) {
		$node = $crawler->filter('body > center > h3');

		$title = $node->filter('font')->first()->text();
		$fio = trim(trim(str_replace($title, '', $node->text())), ':');
		return ['fio' => $fio, 'title' => $title];
	}

	function authorInfo(Crawler $dom) {
		$mapping = [
		'www' => function ($text) {
			return preg_match('"http"i', $text);
		},
		'email' => function ($text) {
			return preg_match('"(http|[^@]+@[^\.]+\..+)"i', $text);
		},
		'birth' => function ($text) {
			return preg_match('"\d+/\d+/\d+"', $text);
		},
		'address' => function ($text) {
			return !(preg_match('"[^@]+@[^\.]+\..+"', $text) || preg_match('"\d+/\d+/\d+"', $text));
		},
		'updated' => function ($text) {
			return preg_match('"\d+/\d+/\d+"', $text);
		},
		'size' => function ($text) {
			return preg_match('"\d+k/\d+"', $text);
		},
		'rating' => function ($text) {
			return preg_match('"[\d\.]\*\d+"', $text);
		},
		'visitors' => function ($text) {
			return preg_match('"\d+"', $text);
		},
		'friends' => function ($text) {
			return preg_match('"\d+(/\d+)?"', $text);
		}];

		$nodes = $dom->filter('li')->each(function (Crawler $node, $id) {
			$parts = explode(':', $node->text());
			array_shift($parts);
			return trim(join(':', $parts));
		});

		$result = [];

		while ($nodes) {
			$node = array_shift($nodes);

			do {
				$keys = array_keys($mapping);
				if (!$keys) {
					debug("Failed to parse author info data");
					break;
				}

				$attribute = $keys[0];

				if ($mapping[$attribute]($node)) {
					$result[$attribute] = $node;
					break;
				}

				array_shift($mapping);
			} while (true);

			if ($mapping)
				array_shift($mapping);
		}

		return $result;
	}

	function authorGroups(array $nodes) {
		$groups = [];
		$genred = [];

		foreach ($nodes as $node) {
			$group = $this->authorGroup($node[0]);
			if ($idx = $group['idx'])
				$groups[$idx] = $group;
			else
				$genred[] = $group;
		};

		return $groups + $genred;
	}

	public function parseGroup($html) {
		$html = mb_convert_encoding($html, 'UTF-8', 'CP1251');
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);

		$pages = $crawler->filter('dl dl')->each(function (Crawler $node) {
			return $this->groupPage($node);
		});
		return $pages;
	}

	function authorGroup(Crawler $groupNode) {
		$data = ['link' => '', 'pages' => []];

		$done = false;
		$groupNode->children()->each(function (Crawler $node) use (&$data, &$done) {
			if ($done)
				return;

			switch (strtolower($node->nodeName())) {
			case 'p':

				$links = $node->filter('b a');

				$name = firstNode($links, function (Crawler $node) {
					return trim($node->attr('name')) != '';
				});
				$title = firstNode($links, function (Crawler $node) {
					return trim($node->text()) != '';
				});
				$link = firstNode($links, function (Crawler $node) {
					return trim($node->attr('href')) != '';
				});

				$data['idx'] = intval(str_replace('gr', '', $name->attr('name')));
				$data['title'] = trim($title->text(), ' :');
				$data['link'] = $link ? $link->attr('href') : null;


				$about = $node->filter('font i')->first();
				$data['annotation'] = trim($about->html());

				break;
			case 'dl':
				$data['pages'][] = $this->groupPage($node);
				break;
			case 'h3':
				$done = true;
			}
		});

		return $data;
	}

	function groupPage(Crawler $node1) {
		$node = $node1->filter('dt')->first();

		if (!count($node)) {
			return false;
		}

		$data = [];

		$link = $node->filter('li>a')->first();
		$data['link'] = $link->attr('href');
		$data['title'] = $link->text();

		$size = $node->filter('li>b')->first();

		if (!count($size) || !preg_match('"\d+k"i', $size->text())) {
			$size = $node->filter('li>b')->eq(1);
			if (!count($size) || !preg_match('"\d+k"i', $size->text())) {
				dd($node->html());
			}
		}
		$data['size'] = intval($size->text()) * 1024;

		$data['rating'] = (count($b = $node->filter('li>small>b'))) ? $b->text() : '';

		if (count($node->filter('li>dd'))) {
			if (count($annotation = $node->filter('li>dd')->eq(0)->filter('font')))
				$data['annotation'] = $annotation->html();
			$data['images'] = !!count($node->filter('li>dd>dd a'));
		}

		return $data;
	}

}

