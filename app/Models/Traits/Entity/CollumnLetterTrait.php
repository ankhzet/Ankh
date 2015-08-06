<?php namespace Ankh\Traits\Entity;

	trait CollumnLetterTrait {
		protected $CollumnLetterTrait_collumn = 'title';

		public function collumn($value = null) {
			if ($value)
				$this->CollumnLetterTrait_collumn = $value;

			return $this->CollumnLetterTrait_collumn;
		}

		public function letterCollumn() {
			return $this->collumn();
		}

		public function getQuery() {
			return static::selectRaw("*");
		}

	}

