<?php namespace Ankh;

use Storage;
use Ankh\Contracts\HtmlCleaner;
use Ankh\Downloadable\CharsetEncoder;

class PageUtils extends CharsetEncoder {

	protected $stored_enc;

	protected $cleaner;
	protected $cache_dir_cmod;

	protected $storage;


	public function __construct(HtmlCleaner $cleaner) {
		parent::__construct();

		$this->stored_enc = 'cp1251';

		$this->cleaner = $cleaner;
		$this->cache_dir_cmod = 0755;

		$this->storage = Storage::disk('page-tvs');
	}

	public function storedEncoding() {
		return $this->stored_enc;
	}

	/**
	 * @return string
	 */
	public function clean($html) {
		return $this->cleaner->clean($html, $this->encoding());
	}

	public function exists(PageResolver $resolver) {
		$path = $resolver->resolve();
		return $this->storage->exists($path) ? $path : false;
	}

	public function local(PageResolver $resolver) {
		$path = $resolver->resolve();
		return $this->storage->exists($path) ? $this->storage->size($path) : false;
	}

	/**
	 * @param PageResolver $resolver
	 * @param string $encoding - Encoding, in which contents should be returned.
	 * @return string
	 */
	public function contents(PageResolver $resolver, $encoding = null) {
		if (!($path = $this->exists($resolver)))
			return null;

		$data = $this->storage->get($path);
		$data = @gzuncompress($data);
		if (!$data)
			return null;

		return $this->wakeup($data, $encoding);
	}

	/**
	 * @param  string $encoding - Encoding, in which contents are encoded.
	 * @return bool
	 */
	public function putContents(PageResolver $resolver, $contents, $encoding = null) {
		$path = $resolver->resolve();

		if (!$this->storage->exists($directory = dirname($path)))
			$this->storage->makeDirectory($directory);

		$stored = $this->storedEncoding();
		if ($stored != ($encoding = $encoding ?: $this->encoding()))
			$contents = $this->transform($contents, $encoding, $stored);

		$data = @gzcompress($contents);

		return $this->storage->put($path, $data);
	}

	public function wakeup($contents, $encoding = null) {
		$encoding = $encoding ?: $this->encoding();

		if (!$this->checkEncoding($contents, $encoding)) {
			$storedEncoding = $this->storedEncoding();
			if (!$this->checkEncoding($contents, $storedEncoding)) {
				$detectedEncoding = $this->detectEncoding($contents);

				// recache if needed
				if ($detectedEncoding != $storedEncoding) {
					$stored = $this->transform($contents, $detectedEncoding, $storedEncoding);
					$this->putContents($resolver, $stored);
				}
			} else
				$detectedEncoding = $storedEncoding;

			if ($detectedEncoding != $encoding)
				$contents = $this->transform($contents, $detectedEncoding, $encoding);
		}

		return $this->clean($contents);
	}

}
