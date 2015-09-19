<?php namespace Ankh\Entity;

use Ankh\Author;
use Ankh\Update;

class UpdatesGroupping {

	protected $authors = [];

	function __construct($foo = null) {
		$this->authors[0] = new Author(['id' => 0, 'fio' => 'Unknown author']);
	}

	public function collect($updates) {
		$grouped = [];

		foreach ($updates as $update)
			array_set($grouped, $this->chunks($update), $update);

		krsort_tree($grouped);

		return $this->glue($grouped);
	}

	function chunks(Update $update) {
		$author_id = 0;
		$author = $update->relatedAuthor();
		if ($author && ($author_id = $author->id)) {
			$this->authors[$author_id] = $author;
		}

		$path = [];

		$path[] = $update->created_at->format('Y-m-d');
		$path[] = $update->created_at->format('H i');
		$path[] = $author_id;
		$path[] = $update->id;

		return join('.', $path);
	}

	function glue($grouped) {
		$r = [];
		foreach ($grouped as $day => $daily)
			$r[$day] = $this->glueDay($daily);

		return $r;
	}

	function glueDay($daily) {
		$r = [];

		$hours = [];
		$id = null;
		$prev_slice = null;
		foreach ($daily as $slice => $sliced) {
			$keys = array_keys($sliced);

			$author = $keys[0];
			if (count($keys) == 1) {
				if ($author == $id || $id === null) {
					$prev_slice = $slice;
					$id = $author;
					$hours = array_append_recursive($hours, $sliced);
					continue;
				}
			}

			if ($prev_slice !== null)
				$r = array_merge($r, [$prev_slice => $hours]);

			$hours = $sliced;
			$id = $author;
			$prev_slice = $slice;
		}

		if ($hours)
			$r = array_merge($r, [$prev_slice => $hours]);

		return $r;
	}

	public function author($id) {
		return @$this->authors[$id] ?: @$this->authors[0];
	}

}
