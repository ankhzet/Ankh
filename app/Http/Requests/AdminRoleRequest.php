<?php namespace Ankh\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRoleRequest extends FormRequest {

	public function rules() {
		return [];
	}

	public function authorize() {
		return \Auth::check() && \Auth::user()->isAdmin();
	}

}
