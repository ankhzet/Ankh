<?php namespace Ankh\Traits\Entity;

use Jenssegers\Date\Date;

trait DateAccessorTrait {

	public function freshTimestamp() {
		return new Date;
	}

	protected function asDateTime($value) {

		if (is_numeric($value)) {

			return Date::createFromTimestamp($value);

		} elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {

			return Date::createFromFormat('Y-m-d', $value)->startOfDay();

		} elseif (! $value instanceof DateTime) {

			$format = $this->getDateFormat();

			return Date::createFromFormat($format, $value);

		}

		return Date::instance($value);
	}

}
