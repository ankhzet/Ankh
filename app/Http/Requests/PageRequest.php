<?php namespace Ankh\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Ankh\Page;

class PageRequest extends EntityRequest {

	public function rules() {
		return [
		'title' => 'required',
		'link' => 'required',
		'annotation' => 'max:1024',
		'size' => 'min:0',
		];
	}

	public function entityClass() {
		return Page::class;
	}

	public function candidate() {
		$group = $this->route('groups');
		if (!$group)
			throw new Exception('Page can be created only if group specified');

		$page = parent::candidate();

		$page->author()->associate($group->author);
		$page->group()->associate($group);

		return $page;
	}

}
