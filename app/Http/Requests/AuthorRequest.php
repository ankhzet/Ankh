<?php namespace Ankh\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Ankh\Author;

class AuthorRequest extends EntityRequest {

	public function rules() {
		return [
		'fio' => 'required',
		'link' => 'required'
		];
	}

	public function getValidatorInstance() {
		$validator = parent::getValidatorInstance();

		$validator->after(function() use ($validator) {

			if (($link = Author::matchLink($this->get('link'))) === false)
				$validator->errors()->add('link', \Lang::get('pages.authors.cant-parse-link'));

		});

		return $validator;
	}

	public function input($key = NULL, $default = NULL) {
		$input = parent::input();

		if ($link = Author::matchLink($input['link']))
			$input['link'] = $link;

		if (!@$input['fio'])
			$input['fio'] = $input['link'];

		return $input;
	}

	public function entityClass() {
		return Author::class;
	}

}
