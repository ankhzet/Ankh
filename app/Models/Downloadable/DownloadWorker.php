<?php namespace Ankh\Downloadable;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Str;

use Ankh\Contracts\Downloadable\Downloadable;

class DownloadWorker extends Response {

	var $resumable = true;
	var $downloadable;
	var $offset = 0;
	var $length;

	public function __construct(Downloadable $downloadable, array $headers = [], $disposition = 'attachment', $addETag = true, $addLastModified = true) {
		parent::__construct(null, 200, $headers);

		// if ($downloadable->extension != Zip::EXTENSION) {
		//	$this->headers->set('Content-Encoding', 'gzip');
		//	$downloadable->setContents(@gzencode($downloadable->getContents()));
		// }

		$this->downloadable = $downloadable;

		if ($disposition)
			$this->setDisposition($disposition);

		if ($addETag)
			$this->addETag();

		if ($addLastModified)
			$this->addLastModified();
	}

	public function prepare(Request $request) {
		$this->headers->set('Content-Length', $this->downloadable->size());

		if (!$this->headers->has('Accept-Ranges')) {
			$this->headers->set('Accept-Ranges', $this->resumable ? 'bytes' : 'none');
		}

		if (!$this->headers->has('Content-Type')) {
			$this->headers->set('Content-Type', $this->getMimeType($request, $this->downloadable) ?: 'application/octet-stream');
		}

		$this->setProtocolVersion('1.1');

		$this->offset = 0;
		$this->length = $this->downloadable->size();
		list($from, $to, $size) = static::range($request, $this->downloadable);

		if ($this->resumable) {
			if (($from != 0) || ($to != $size)) {
				if ($size == 0)
					$this->setStatusCode(416);
				else {
					$this->setStatusCode(206);
					$this->headers->set('Content-Range', "bytes $from-$to/{$this->length}");
					$this->headers->set('Content-Length', $size);

					$this->offset = $from;
					$this->length = $size;
				}
			}
		}

		return $this;
	}

	public function sendContent() {
		$size = $this->length;
		$data = substr($this->downloadable->getContents(), $this->offset, $size);

		if (!$out = fopen('php://output', 'wb'))
			return;

		try {
			$chunk = min(max(intval($size / 10), 1024), min(32 * 1024, $size));

			$pos = 0;
			while ($pos < $size) {
				$buf = min($size - $pos, $chunk);
				fwrite($out, substr($data, $pos, $buf));
				$pos += $buf;
			}
		} finally {
			fclose($out);
		}

        return $this;
	}

	public function setDisposition($disposition) {
		$filename = basename($this->downloadable->filename());
		$filenameFallback = str_replace('%', '', Str::ascii($filename));
		if ($filenameFallback == $filename)
			$filenameFallback = '';

		$dispositionHeader = $this->headers->makeDisposition($disposition, $filename, $filenameFallback);
		$this->headers->set('Content-Disposition', $dispositionHeader);

		return $this;
	}

	public function addETag() {
		$this->setEtag(sha1($this->downloadable->getContents()));
		return $this;
	}

	public function addLastModified() {
		$this->setLastModified($this->downloadable->datetime());

		return $this;
	}

	static function ua(Request $request) {
		$ua = $request->server->get('HTTP_USER_AGENT');
		if (preg_match('"(Opera|MSIE|^[^\s]+)[/\s]*(\d.\d+)"i', $ua, $m))
			return strtolower($m[1]);
		else
			return null;
	}

	static function getMimeType(Request $request, Downloadable $downloadable) {
		if (str_contains(static::ua($request), ['opera', 'msie']))
			return 'application/octetstream';

		return false;
	}

	static function range(Request $request, Downloadable $downloadable) {
		$size = $downloadable->size();
		$from = 0;
		$to = $size;

		if($range = $request->headers->get('Range')) {
			$range = trim(substr($range, strpos($range, '=') + 1)) . '-';
			list($from, $to) = explode("-", $range);

			if ('' === $to) {
				$to = $size;
			}

			$from = max((int)$from, 0);
			$to = min((int)$to, $size);
			$size = $to - $from + 1;
		}

		return [$from, $to, $size];
	}

}
