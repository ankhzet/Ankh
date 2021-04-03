<?php namespace Ankh;

use Storage;
use Ankh\Contracts\HtmlCleaner;
use Ankh\Downloadable\CharsetEncoder;

class PageUtils extends CharsetEncoder {

	protected $stored_enc;

	protected $cleaner;
	protected $cache_dir_cmod;

	/** @var Storage */
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
	 * @param string $html
	 * @return string
	 */
	public function clean(?string $html): string {
		return $html ? $this->cleaner->clean($html, $this->encoding()) : '';
	}

	/**
	 * @param PageResolver $resolver
	 * @return bool|string
	 */
	public function exists(PageResolver $resolver) {
		$path = $resolver->resolve();

		return ($path && $this->storage->exists($path)) ? $path : false;
	}

	/**
	 * @param PageResolver $resolver
	 * @return bool
	 */
	public function delete(PageResolver $resolver) {
		$path = $resolver->resolve();

		return $path ? $this->storage->delete($path) : false;
	}

	/**
	 * @param PageResolver $resolver
	 * @return bool|int
	 */
	public function local(PageResolver $resolver) {
		$path = $resolver->resolve();

		return ($path && $this->storage->exists($path)) ? $this->storage->size($path) : false;
	}

	/**
	 * @param PageResolver $resolver
	 * @param string $encoding - Encoding, in which contents should be returned.
	 * @return string|null
	 */
	public function contents(PageResolver $resolver, string $encoding = null) {
		if (!($path = $this->exists($resolver))) {
			return null;
		}

		$data = $this->storage->get($path);
		$data = @gzuncompress($data);

		if (!$data) {
			return null;
		}

		return $this->wakeup($data, $resolver, $encoding);
	}

	/**
	 * @param PageResolver $resolver
	 * @param string $contents
	 * @param string $encoding - Encoding, in which contents are encoded.
	 * @return bool
	 */
	public function putContents(PageResolver $resolver, string $contents, string $encoding): bool {
		$path = $resolver->resolve();

		if (!$this->storage->exists($directory = dirname($path))) {
			$this->storage->makeDirectory($directory);
		}

		$stored = $this->storedEncoding();

		if ($stored !== $encoding) {
			$contents = $this->transform($contents, $encoding, $stored);
		}

		$data = @gzcompress($contents);

		return $this->storage->put($path, $data);
	}

	/**
	 * @param string $contents
	 * @param PageResolver $resolver
	 * @param string|null $encoding
	 * @return string
	 */
	public function wakeup(string $contents, PageResolver $resolver, string $encoding = null): string {
		$encoding = $encoding ?: $this->encoding();

		if (!$this->checkEncoding($contents, $encoding)) {
			$storedEncoding = $this->storedEncoding();

			if (!$this->checkEncoding($contents, $storedEncoding)) {
				$detectedEncoding = $this->detectEncoding($contents);

				// re-cache if needed
				if ($detectedEncoding != $storedEncoding) {
					$stored = $this->transform($contents, $detectedEncoding, $storedEncoding);
					$this->putContents($resolver, $stored, $storedEncoding);
				}
			} else {
				$detectedEncoding = $storedEncoding;
			}

			if ($detectedEncoding != $encoding) {
				$contents = $this->transform($contents, $detectedEncoding, $encoding);
			}
		}

		return $contents;
	}

}
