<?php namespace Ankh\Downloadable;

use Exception;

use Ankh\Contracts\Downloadable\Transformation;
use Ankh\Contracts\Downloadable\Transformable;

class CharsetEncoder implements Transformation {

	protected $used_enc;
	protected $map = ['cp1251' => 'win1251'];
	protected $rmap = ['win1251' => 'cp1251'];

	public function __construct() {
		$this->used_enc = 'UTF-8';
		mb_detect_order(['ASCII', 'CP1251', 'UTF-8']);
	}

	public function apply(Transformable $transformable, $toEncoding = null) {
		$data = (string)$transformable;
		$data = $this->transform($data, $this->detectEncoding($data), $toEncoding);

		if (!!$data)
			$transformable->charset = $this->remap($this->detectEncoding($data));

		return $transformable->setContents($data);
	}

	public function remap($encoding, $reverse = false) {
		$map = $reverse ? $this->rmap : $this->map;
		return @$map[$encoding] ?: $encoding;
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

