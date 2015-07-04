<?php namespace Ankh\Admin\Columns;

use SleepingOwl\Admin\Columns\Column\Url;

class FormattedUrlColumn extends Url
{

	/**
	 * Url formatter callback.
	 *
	 * @var Closure
	 */
	protected $formatter = null;

	public function valueFromInstance($instance, $totalCount) {
		$url = parent::valueFromInstance($instance, $totalCount);
		if ($this->formatter) {
			$formatter = $this->formatter;
			$url = $formatter($url);
		}

		return $url;
	}

	public function formatted(\Closure $formatter) {
		$this->formatter = $formatter;
		return $this;
	}
}
