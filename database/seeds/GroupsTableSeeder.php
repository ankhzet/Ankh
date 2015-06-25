<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;
	use Ankh\Group;

	class GroupsTableSeeder extends CommonSeeder {
		const GROUPS_TO_SEED = 100;

		public function run() {
			$authors = Author::all();
			$this->iterate(self::GROUPS_TO_SEED / 2, self::GROUPS_TO_SEED, function ($i) use ($authors) {
				$this->createGroup($authors->random(1));
			});
		}

		function createGroup(Author $author) {
			$faker = $this->faker();
			$group = new Group;
			$group->title = join(' ', $faker->words(5));
			$group->link = $faker->boolean() ? '/' . $faker->slug(3) . '.html' : '';
			$group->inline = $faker->boolean();
			$group->annotation = $faker->realText();
			$group->author()->associate($author);
			$group->save();

			return $group;
		}


	}
