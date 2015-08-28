<?php namespace Ankh\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Ankh\Group;

class GroupRequest extends EntityRequest {

	public function rules() {
		return [
		'title' => 'required',
		'inline' => 'boolean',
		'annotation' => 'max:1024',
		];
	}

	public function entityClass() {
		return Group::class;
	}

	public function candidate() {
		$author = $this->route('authors');
		if (!$author)
			throw new Exception('Group can be created only if author specified');

		$group = parent::candidate();

		$group->author()->associate($author);

		return $group;
	}

}
