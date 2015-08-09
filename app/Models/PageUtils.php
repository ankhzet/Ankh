<?php namespace Ankh;

use File;
use Ankh\Contracts\HtmlCleaner;

class PageUtils {

	protected $cleaner;
	protected $cache_dir_cmod;
	protected $stored_enc;
	protected $used_enc;

	public function __construct(HtmlCleaner $cleaner) {
		$this->cleaner = $cleaner;
		$this->cache_dir_cmod = 0755;
		$this->stored_enc = 'cp1251';
		$this->used_enc = 'utf8';
	}

	/**
	 * @return string
	 */
	public function clean($html) {
		return $this->cleaner->clean($html, 'utf8');
	}

	/**
	 * @return string
	 */
	public function contents(PageResolver $resolver) {
		$path = $resolver->resolve();
		if (!File::exists($path))
			return null;

		$data = File::get($path);
		$data = @gzuncompress($data);

		if (!$this->checkEncoding($data)) {
			$storedEncoding = $this->stored_enc;
			if (!$this->checkEncoding($data, $storedEncoding)) {
				$storedEncoding = $this->detectEncoding($data);

				// recache if needed
				if ($storedEncoding != $this->stored_enc) {
					$stored = @mb_convert_encoding($data, $this->stored_enc, $storedEncoding);
					$this->putContents($resolver, $data);
				}
			}

			$data = @mb_convert_encoding($data, $this->used_enc, $storedEncoding);
		}

		return $data;
	}

	/**
	 * @return bool
	 */
	public function putContents(PageResolver $resolver, $contents) {
		$cleanedContents = $this->clean($contents);
		$path = $resolver->resolve();

		if (!File::isDirectory($directory = dirname($path)))
			File::makeDirectory($directory, $this->cache_dir_cmod, true);

		if ($this->stored_enc != $this->used_enc)
			$data = @mb_convert_encoding($data, $this->stored_enc, $this->used_enc);

		$data = @gzcompress($cleanedContents);

		return File::put($path, $data);
	}


	/**
	 * @return bool
	 */
	public function checkEncoding($text, $encoding = null, $sample = 200) {
		if ($encoding === null)
			$encoding = $this->used_enc;

		if (strlen($text) > $sample)
			$text = substr($text, 0, $sample);

		return mb_check_encoding($text, $encoding);
	}

	/**
	 * @return string
	 */
	public function detectEncoding($text) {
		throw new Exception('Can\'t decode cached text, "{$this->stored_enc}" or "{$this->used_enc}" encoding expected');
	}

}
