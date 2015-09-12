<?php namespace Ankh;

use Exception;

class CharsetEncoder {

	protected $used_enc;

	public function __construct() {
		$this->used_enc = 'UTF-8';
		mb_detect_order(['ASCII', 'CP1251', 'UTF-8']);
	}

	public function transform($data, $fromEncoding, $toEncoding = null) {
		$toEncoding = $toEncoding ?: $this->encoding();
		if ($fromEncoding == $toEncoding)
			return $data;

		$data = @mb_convert_encoding($data, $toEncoding, $fromEncoding);
		return $data;
	}

	public function encoding() {
		return $this->used_enc;
	}

	/**
	 * @return bool
	 */
	public function checkEncoding($text, $encoding = null, $sample = 200) {
		if ($encoding === null)
			$encoding = $this->encoding();

		if (strlen($text) > $sample)
			$text = substr($text, 0, $sample);

		return mb_check_encoding($text, $encoding);
	}

	/**
	 * @return string
	 */
	public function detectEncoding($text, $sample = 1024) {
		if (strlen($text) > $sample)
			$text = substr($text, 0, $sample);

		if (($t = mb_convert_encoding($text, 'cp1251')) !== '') {
			if ($this->checkEncoding($text, 'cp1251'))
				return 'cp1251';
		}

		return mb_detect_encoding($text);
	}


}

