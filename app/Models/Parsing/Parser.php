<?php namespace Ankh\Parsing;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Parser {

	public function parseAuthor($html) {
		$html = mb_convert_encoding($html, 'UTF-8', 'CP1251');
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);

		$infoTbl = $crawler->filter('table')->first();

		$info = $this->authorInfo($infoTbl);
		$about = trim($infoTbl->nextAll()->filter('font > i')->first()->html());
		$groups = $this->authorGroups($g = $crawler->filter('j'));

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

	function authorGroups(Crawler $nodes) {
		$groups = [];

		$nodes->each(function (Crawler $node) use (&$groups) {

			$groupNodes = $node->filter('j')->each(function (Crawler $node) {
				return $node;
			});

			foreach ($groupNodes as $node) {
				$group = $this->authorGroup($node);
				$groups[$group['idx']] = $group;
			};

		});

		return $groups;
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

	function authorGroup(Crawler $node) {
		$data = ['link' => ''];

		$p = $node->filter('p>font>b');
		$p = $p->parents()->each(function (Crawler $node) {
			return $node;
		});
		$p = Arr::first($p, function ($id, $node) {
			return strtolower($node->nodeName()) == 'p';
		});
		$p->filter('a')->each(function (Crawler $node) use (&$data) {
			if ($name = $node->attr('name'))
				$data['idx'] = intval(str_replace('gr', '', $name));

			if ($text = $node->text())
				$data['title'] = trim($text, ' :');

			if ($link = $node->attr('href'))
				$data['link'] = $link;
		});

		$about = $node->filter('font i')->first();
		$data['annotation'] = trim($about->html());


		$pages = $node->filter('dl')->each(function (Crawler $node) {
			return $this->groupPage($node);
		});
		$data['pages'] = $pages;

		return $data;
	}

	function groupPage(Crawler $node) {
		$node = $node->filter('dt')->first();

		$data = [];

		$link = $node->filter('li>a')->first();
		$data['link'] = $link->attr('href');
		$data['title'] = $link->text();

		$data['size'] = intval($node->filter('li>b')->first()->text()) * 1024;

		if (!$data['size'])
			$data['size'] = intval($node->filter('li>b')->eq(1)->text()) * 1024;

		$data['rating'] = (count($b = $node->filter('li>small>b'))) ? $b->text() : '';

		if (count($node->filter('li>dd'))) {
			if (count($annotation = $node->filter('li>dd')->eq(0)->filter('font')))
				$data['annotation'] = $annotation->html();
			$data['images'] = !!count($node->filter('li>dd>dd a'));
		}

		return $data;
	}

}

