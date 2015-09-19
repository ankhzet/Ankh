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

	/**
	 * @return string
	 */
	public function clean($html) {
		return $this->cleaner->clean($html, $this->encoding());
	}

	/**
	 * @return string
	 */
	public function contents(PageResolver $resolver, $encoding = null) {
		$path = $resolver->resolve();
		if (!$this->storage->exists($path))
			return null;

		$data = $this->storage->get($path);
		$data = @gzuncompress($data);
		if (!$data)
			return null;

		if (!$this->checkEncoding($data, $encoding)) {
			$storedEncoding = $this->stored_enc;
			if (!$this->checkEncoding($data, $storedEncoding)) {
				$storedEncoding = $this->detectEncoding($data);

				// recache if needed
				if ($storedEncoding != $this->stored_enc) {
					$data = $this->transform($data, $storedEncoding, $this->stored_enc);
					$this->putContents($resolver, $data);
					$storedEncoding = $this->stored_enc;
				}
			}



			if ($storedEncoding != ($encoding ?: $this->encoding()))
				$data = $this->transform($data, $storedEncoding, $encoding);
		}

		return $this->clean($data);
	}

	/**
	 * @return bool
	 */
	public function putContents(PageResolver $resolver, $contents, $encoding = null) {
		$path = $resolver->resolve();

		if (!$this->storage->isDirectory($directory = dirname($path)))
			$this->storage->makeDirectory($directory);

		if ($this->stored_enc != ($encoding = $encoding ?: $this->encoding()))
			$contents = $this->transform($contents, $encoding, $this->stored_enc);

		$data = @gzcompress($contents);

		return $this->storage->put($path, $data);
	}

}
