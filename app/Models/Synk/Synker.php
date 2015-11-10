<?php namespace Ankh\Synk;

use Ankh\Entity;

class Synker {

	const CREATED = 'created';
	const UPDATED = 'updated';
	const DELETED = 'deleted';
	const RESTORED= 'restored';

	const OLD     = '_old';

	protected $parent;
	protected $attribute;
	protected $associate;

	protected $statistics = [];

	function __construct(Entity $parent, $attribute, $associate = null) {
		$this->parent = $parent;
		$this->attribute = $attribute;
		$this->associate = $associate ?: strtolower(class_basename($parent));
	}

	function synk(array $data) {
		if (!$data)
			return [];

		// first, update entities by strict match
		list($unbound, $new) = $this->cross($this->current(), $data);
		// update entities, that was renamed/moved to other location
		list($unbound, $new) = $this->cross($unbound, $new, false);


		// create new entities
		foreach ($new as $data)
			$this->create($this->dataToEntityData($data));

		// delete unbound entities
		foreach ($unbound as $entity)
			$this->delete($entity);

		return $this->statistics;
	}

	function current() {
		return $this->parent->{$this->attribute}()->get()->all();
	}

	function cross($current, $synk, $strictMatch = true) {
		$new = [];
		$unbound = array_merge($current, []);
		foreach ($synk as $data) {
			$found = false;
			foreach ($current as $entity)
				if ($found |= static::same($entity, $data, $strictMatch)) {
					$this->update($entity, $this->dataToEntityData($data));

					$unbound = array_where($unbound, function ($k, $v) use ($entity) {
						return $v != $entity;
					});
					break;
				}

			if (!$found)
				$new[] = $data;
		}
		return [$unbound, $new];
	}

	protected function log($type, $value, Entity $entity = null) {
		if (is_array($value)) {
			$value = array_filter(array_filter($value, function ($property) {
				return !is_array($property);
			}));

			if ($value) {
				if (!($value = $entity->filterImportantUpdatedAttributes($value)))
					return;
			}

			$value['id'] = $entity->id;
		} else
			$value = array_merge(['id' => $entity->id], @$value);

		$this->statistics[$type][] = $value;
	}

	function create(array $data) {
		if ($entity = $this->createEntity($data)) {

			$entity->{$this->associate}()->associate($this->parent);
			$entity->save();

			$this->log(static::CREATED, $data, $entity);
		}
	}

	function update(Entity $entity, array $data) {
		if ($diff = $this->updateEntity($entity, $data)) {
			$this->log(static::UPDATED, $diff, $entity);
		}
	}

	function delete(Entity $entity) {
		if ($this->deleteEntity($entity))
			$this->log(static::DELETED, [], $entity);
	}

	protected function createEntity(array $data) {
		return new Entity($data);
	}

	protected function updateEntity(Entity $entity, array $data) {
		$entity = $entity->fill($data);
		$shouldSave = !!($diff = $entity->diffAttributes());

		if ($diff) {
			$old = $entity->getOriginal();
			$diff[static::OLD] = array_intersect_assoc($old, $diff);
		}

		if ($entity->trashed()) {
			if ($entity->restore()) {
				$diff[static::RESTORED] = true;
				$shouldSave = false;
			}
		}

		$diff = $diff ?: false;

		if ($shouldSave && !$entity->save())
			$diff = false;

		return $diff;
	}

	protected function deleteEntity(Entity $entity) {
		return $entity->delete();
	}

	public static function same(Entity $entity, array $data, $strict = true) {
		return false;
	}

	public static function pick(Entity $entity, array $data) {
		return array_first($data, function ($k, $data) use ($entity) {
				return static::same($entity, $data);
			});
	}

	public static function select($entities, array $data) {
		return array_first($entities, function ($k, $entity) use ($data) {
				return static::same($entity, $data);
			});
	}

	protected function dataToEntityData(array $data) {
		return $data;
	}

}

