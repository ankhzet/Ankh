<?php namespace Ankh\Http\Requests;

class AuthorCreateRequest extends AuthorRequest {

	public function rules() {
		return [
		'link' => 'required|unique:authors'
		];
	}

	public function authorize() {
		return \Auth::check();
	}

}
