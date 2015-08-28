<?php namespace Ankh\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Ankh\Entity;

class EntityRequest extends AdminRoleRequest {

	public function rules() {
		return [];
	}

	public function data() {
		$data = $this->all();
		return array_except($data, array_filter(array_keys($data), function ($key) {
			return starts_with($key, '_') || (strtolower($key) == 'deleted');
		}));
	}

	public function deleted() {
		return !!$this->get('deleted');
	}

	public function entityClass() {
		return Entity::class;
	}

	public function candidate() {
		$class = $this->entityClass();
		return new $class($this->data());
	}

}
