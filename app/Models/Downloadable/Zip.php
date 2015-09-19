<?php namespace Ankh\Downloadable;

use Carbon\Carbon;
use Ankh\Contracts\Downloadable\Zipable;

class Zip {

	const EXTENSION = 'zip';

																										//  \x08 for unicode
	const ZIP_DIR  = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00";
	const ZIP_FILE = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00";
	const ZIP_EOCD = "\x50\x4b\x05\x06\x00\x00\x00\x00";

	var $data      = '';
	var $directory = '';
	var $entries   = 0;
	var $file_num  = 0;
	var $offset    = 0;

	var $comment   = '';

	public function singleFile(Zipable $zipable) {
		$this->comment = $zipable->comment();

		if (!$this->put($zipable))
			return null;

		$zipable->setType(static::EXTENSION);
		return $this;
	}

	public function put(Zipable $zipable) {
		$filepath = str_replace("\\", "/", $zipable->path());

		$filepath = mb_convert_encoding($filepath, 'cp866');

		$uncompressed_size = strlen($data = $zipable->data());
		$crc32  = crc32($data);

		$gzdata = gzcompress($data);
		$gzdata = substr($gzdata, 2, -4);
		$compressed_size = strlen($gzdata);

		$time = $this->cvtDateTime($zipable->datetime());

		$header =
		pack('v2V3v2',
			$time[0],
			$time[1],
			$crc32,
			$compressed_size,
			$uncompressed_size,
			strlen($filepath),
			0
			);

		$this->data .=
		static::ZIP_FILE
		. $header
		. $filepath
		. $gzdata; // "file data" segment

		$this->directory .=
		static::ZIP_DIR
		. $header
		. pack('v*', 0, 0, 0) // file comment length, disk number start, internal file attributes
		. pack('V*', 32, $this->offset) // external file attributes - 'archive' bit set, relative offset of local header
		. $filepath;

		$this->offset = strlen($this->data);
		$this->entries++;
		$this->file_num++;

		return true;
	}

	public function __toString() {
		$comment = @mb_convert_encoding($this->comment, 'cp1251', mb_detect_encoding($this->comment));
		$zip_data = $this->data;
		$zip_data.= $this->directory . static::ZIP_EOCD;
		$zip_data.= pack('v2V2v',
			$this->entries,
			$this->entries,
			strlen($this->directory),
			strlen($this->data),
			strlen($comment)
			);
		$zip_data.= $comment;

		return $zip_data;
	}

	function cvtDateTime(Carbon $datetime) {
		return [
		0 /*'file_mtime'*/ => ($datetime->hour << 11) + ($datetime->minute << 5) + $datetime->second / 2,
		1 /*'file_mdate'*/ => (($datetime->year - 1980) << 9) + ($datetime->month << 5) + $datetime->day,
		];
	}

}

