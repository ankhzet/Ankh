<?php namespace Ankh;

use Carbon\Carbon;
use Exception;

use Ankh\Contracts\Resolvable;
use Ankh\Downloadable\FileVersion;
use PageUtils;

class Version implements Resolvable {
	static $DEF_ENCODING = 'UTF-8';

	/**
	 * @var Page
	 */
	protected $entity;

	/**
	 * @var Carbon $time
	 */
	protected $time;

	/**
	 * @param Carbon|string|integer|null $timestamp
	 */
	public function __construct($timestamp = null) {
		if ($timestamp)
			$this->setTimestamp($timestamp);
	}

	public function setEntity(Resolvable $entity) {
		$this->entity = $entity;

		return $this;
	}

	public function entity() {
		return $this->entity;
	}

	public function getAttribute($name)
	{
		return $this->{$name};
	}

	public function timestamp() {
		return $this->time ? $this->time->timestamp : 0;
	}

	public function time() {
		return $this->time;
	}

	/**
	 * @param Carbon|string|integer|null $timestamp
	 * @return $this
	 */
	public function setTimestamp($timestamp) {
		switch (true) {
		case is_object($timestamp):
			$this->time = $timestamp;
			break;
		case is_string($timestamp):
			$this->time = Carbon::createFromFormat('d-m-Y\+H-i-s', $timestamp);
			break;
		case is_numeric($timestamp):
			$this->time = Carbon::createFromTimestamp($timestamp);
			break;
		default:
			$timestamp = e($timestamp);

			/** @noinspection PhpUnhandledExceptionInspection */
			throw new Exception("Don't know how to interpret [{$timestamp}] version identifier");
		}

		return $this;
	}

	public function encode($format = 'd-m-Y+H-i-s'): string {
		return $this->time->format($format);
	}

	public function __toString(): string {
		return $this->encode();
	}

	public function resolver(): PageResolver {
		/** @var PageResolver $resolver */
		$resolver = $this->entity()->resolver();

		return $resolver->setVersion($this);
	}

	public function contents() {
		return PageUtils::clean(
			PageUtils::contents($this->resolver(), self::$DEF_ENCODING)
		);
	}

	public function exists(): bool {
		return PageUtils::exists($this->resolver());
	}

	public function delete(): bool {
		return PageUtils::delete($this->resolver());
	}

	public function setContents($data) {
		return PageUtils::putContents($this->resolver(), $data, self::$DEF_ENCODING);
	}

	public function downloadable() {
		return (new FileVersion())
			->setPage($this->entity())
			->setVersion($this);
	}

}
