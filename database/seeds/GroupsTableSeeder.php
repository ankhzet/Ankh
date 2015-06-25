<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;
	use Ankh\Group;

	class GroupsTableSeeder extends Seeder {
		var $faker = null;

		public function run() {
			$faker = $this->faker();

			$authors = Author::all();
			for ($i = 0; $i < $faker->numberBetween(1, 100); $i++) {
				$group = $this->createGroup($authors->random(1));
			}

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


		function faker() {
			return $this->faker ?: ($this->faker = Faker\Factory::create());
		}
	}
