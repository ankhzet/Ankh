<?php namespace Ankh\Page;

use \SebastianBergmann\Diff\Differ;

class Diff {

	public function diff($t1, $t2) {
		$t1 = str_replace(['<dd>', '</dd>'], [PHP_EOL, ''], $t1);
		$t2 = str_replace(['<dd>', '</dd>'], [PHP_EOL, ''], $t2);

		$differ = new Differ();

		$diffs = $differ->diffToArray($t1, $t2);

		$folders = [new ContextFolder, new ReplacesFolder/**/, new TypeFolder(PHP_EOL), new DiffFolder];
		foreach ($folders as $folder)
			$diffs = $folder->fold($diffs);

		return $diffs;
	}

}

function un_diff($diff) {
	return is_array($diff) ? $diff[0] : $diff;
}

class Folder {

	protected $folder = '';

	protected $buffer = [];
	protected $chunks = [];

	public function __construct($folder = null) {
		if ($folder)
			$this->folder = $folder;
	}

	public function fold(array $diffs) {
		$this->run($diffs);
		return $this->chunks;
	}

	protected function run(array $diffs) {
		foreach ($diffs as $diff){
			switch ($type = $diff[1]) {
			case 0:
				$this->old(un_diff($diff[0]));
				break;
			case 1:
				$this->ins(un_diff($diff[0]));
				break;
			case 2:
				$this->del(un_diff($diff[0]));
				break;
			default:
				$this->chunk($type, un_diff($diff[0]));
			}
		}
	}

	protected function old($token) {
		$this->chunk(0, $token);
	}

	protected function ins($token) {
		$this->chunk(1, $token);
	}

	protected function del($token) {
		$this->chunk(2, $token);
	}

	protected function buffer($token, $buffer = 'buffer') {
		$this->{$buffer}[] = $token;
	}

	protected function chunk($type, $token) {
		$this->chunks[] = [0 => $token, 1 => $type];
	}

	protected function clearBuffer($buffer = 'buffer') {
		$this->{$buffer} = [];
	}

	protected function foldBuffer() {
	}

	protected function foldChunks($chunks, $folder = null) {
		return join($folder ?: $this->folder, array_filter(array_map('\Ankh\Page\un_diff', $chunks)));
	}

}

class DiffFolder extends Folder {

	protected $folder = PHP_EOL;

	public function fold(array $diffs) {
		return $this->foldChunks(parent::fold($diffs));
	}

	protected function old($token) {
		$this->chunk(0, "<span class=\"context\">" . $token . "</span>");
	}

	protected function ins($token) {
		$this->chunk(1, "<ins>" . $token . "</ins>");
	}

	protected function del($token) {
		$this->chunk(2, "<del>" . $token . "</del>");
	}

}

class ContextFolder extends Folder {

	var $context = 200;

	protected function run(array $diffs) {
		parent::run($diffs);
		$this->foldContext();
	}

	protected function old($token) {
		$this->buffer($token);
	}

	protected function ins($token) {
		$this->foldContext();
		$this->chunk(1, $token);
	}

	protected function del($token) {
		$this->foldContext();
		$this->chunk(2, $token);
	}

	public function foldContext() {
		if (!count($buffer = $this->buffer))
			return;

		$joiner = "<hr class=\"context-sep\" />";

		if (strlen(join(PHP_EOL, $buffer)) > $this->context * 2) {
			$l = [];
			$r = [];
			while ($buffer && strlen(join(PHP_EOL, $l)) < $this->context) {
				$l[] = array_shift($buffer);
			}

			while ($buffer && strlen(join(PHP_EOL, $r)) < $this->context) {
				$r[] = array_pop($buffer);
			}

			$buffer = array_merge($l, [$joiner], array_reverse($r));
		}

		$folded = join(PHP_EOL, $buffer);

		$this->chunk(0, $folded);
		$this->clearBuffer();
	}

}

class ReplacesFolder extends Folder {

	protected $delete = [];
	protected $insert = [];

	protected function run(array $diffs) {
		parent::run($diffs);
		$this->foldReplaces();
	}

	protected function old($token) {
		$this->foldReplaces();
		$this->chunk(0, $token);
	}

	protected function ins($token) {
		$this->buffer($token, 'insert');
	}

	protected function del($token) {
		$this->buffer($token, 'delete');
	}

	public function foldReplaces() {
		$marker = chr(1);
		$marker2 = chr(2);
		$del = $this->foldChunks($this->delete, $marker);
		$ins = $this->foldChunks($this->insert, $marker);

		$replacer = function ($match) use ($marker2) {
			return str_replace(' ', $marker2, $match[1]);
		};
		$del = preg_replace_callback('#(<\w+([^>]+)>)#i', $replacer, $del);
		$ins = preg_replace_callback('#(<\w+([^>]+)>)#i', $replacer, $ins);

		$del = str_replace([' ', '><'], [PHP_EOL, '>' . PHP_EOL . '<'], $del);
		$ins = str_replace([' ', '><'], [PHP_EOL, '>' . PHP_EOL . '<'], $ins);

		$differ = new \SebastianBergmann\Diff\Differ();

		$diffs = $differ->diffToArray($del, $ins);

		$folded = FolderChain::fold([new TypeFolder(' '), new ContextTypeRemover, new DiffFolder(' ')], $diffs);

		$folded = str_replace($marker, PHP_EOL, $folded);
		$folded = str_replace($marker2, ' ', $folded);

		$this->chunk(3, $folded);
		$this->clearBuffer('delete');
		$this->clearBuffer('insert');
	}

}

class TypeFolder extends Folder {

	protected $type;

	protected function run(array $diffs) {
		parent::run($diffs);
		$this->foldBuffer();
	}

	protected function chunk($type, $chunk) {
		if ($type != $this->type)
			$this->foldBuffer();

		$this->type = $type;
		$this->buffer(un_diff($chunk));
	}

	public function foldBuffer() {
		$folded = $this->foldChunks($this->buffer);
		if ($folded == '')
			return;

		parent::chunk($this->type, $folded);
		$this->clearBuffer();
	}

}

class ContextTypeRemover extends Folder {

	protected function old($chunk) {
		$this->chunk(3, $chunk);
	}

}

class FolderChain {

	public static function fold(array $folders, array $diffs) {
		foreach ($folders as $folder) {
			$diffs = $folder->fold($diffs);
		}

		return $diffs;
	}

}
