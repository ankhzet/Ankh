<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;
	use Ankh\Group;
	use Ankh\Page;

	class PagesTableSeeder extends CommonSeeder {
		const PAGES_TO_SEED = 500;

		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run() {
			$authors = Author::all();

			$this->iterate(self::PAGES_TO_SEED / 2, self::PAGES_TO_SEED, function ($i) use ($authors) {
				$author = $authors->random();
				do {
					$author = $authors->random();
				} while ($author->groups->isEmpty());

				$faker = $this->faker();

				$page = new Page;
				$page->title = $faker->sentence(5);
				$page->annotation = join(' ', $faker->sentences(5));
				$page->link = $faker->slug(3);
				$page->size = $faker->numberBetween(0, 9999);
				$page->author()->associate($author);
				$page->group()->associate($author->groups->random());
				$page->save();
			});
		}

	}
