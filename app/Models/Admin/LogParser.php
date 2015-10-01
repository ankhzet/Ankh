<?php namespace Ankh\Admin;

use Carbon\Carbon;
use Request;

class LogParser {
	const LIMIT = 1000;

	var $doc_root;
	var $dbg_root;

	function __construct() {
		$this->dbg_root = rtrim(config('app.debug_src'), '/');
		$this->doc_root = rtrim(preg_replace('#/public$#', '', str_replace('\\', '/', Request::server('DOCUMENT_ROOT'))), '/');
	}

	public function parse($log) {
		$lines = str_replace("\r\n", "\n", $log);
		$lines = explode("\n", $lines);

		$log = [];
		$chunk = [];
		$c = static::LIMIT;
		while ($lines && ($c > 0)) {
			$line = array_shift($lines);
			if (preg_match("#(^\[\d.*?)#", $line)) {
				$log[] = $this->parseEntry($chunk);
				$c--;

				$chunk = [$line];
			} else
				$chunk[] = $line;
		}
		$log[] = $this->parseEntry($chunk);

		$l = [];
		foreach (array_filter($log) as $entry)
			$l[$entry['date']->timestamp] = $entry;

		krsort($l);

		return $l;
	}

	public function parseEntry($entry) {
		$entry = array_filter($entry);
		if (!$entry)
			return null;

		preg_match('#\[([^]]+)]([^:]+):(.*)#', $m = array_shift($entry), $match);

		$entry = array_filter(array_map(function ($line) {
			$trimmed = ltrim($line, '#0..9 ');
			if ($trimmed == $line)
				return null;

			$r = [];
			if (preg_match('#(.+?)\((\d+)\):\s*(.*)#', $trimmed, $m)) {
				$r['file'] = $this->parseFile($m[1]);
				$r['line'] = (int) $m[2];
				$r['code'] = $m[3];
			} else
				$r = ['code' => trim(str_replace('[internal function]: ', '', $trimmed))];

			return $r;
		}, $entry));

		$message = $this->parseMessage(trim($match[3]));

		return [
		'date' => new Carbon($match[1]),
		'level' => str_replace('local.', '', strtolower(trim($match[2]))),
		'message' => $message,
		'stack' => $entry,
		];

	}

	public function parseMessage($message) {
		if (preg_match("#exception '(.+)' with message '(.+)' in (.*):(\d+)#i", $message, $m))
			return [
				'exception' => $m[1],
				'message' => $m[2],
				'file' => $this->parseFile($m[3]),
				'line' => $m[4],
			];

		return $message;
	}

	public function parseFile($file) {
		return str_replace($this->doc_root, $this->dbg_root, str_replace('\\', '/', $file));
	}
}
