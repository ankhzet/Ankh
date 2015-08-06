<?php namespace Ankh\Traits\Entity\Repository;

use Ankh\Entity\Filters\LetterFilter;

trait LetterFilterTrait {

	protected $LetterFilterTrait_letter = 'letter';

	public function addLetterFilter($letter) {
		$this->addFilter($this->LetterFilterTrait_letter, new LetterFilter($letter));
	}

	public function letterFilter() {
		return $this->filter($this->LetterFilterTrait_letter);
	}

	public function lettersUsage() {
		return $this->letterFilter()->lettersUsage($this->model(), $this->filters());
	}

}
