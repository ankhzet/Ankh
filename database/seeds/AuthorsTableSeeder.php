<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;

	class AuthorsTableSeeder extends Seeder {
		const AUTHORS_TO_SEED = 200;
		var $fakers = null;

		public function run() {

			$faker = $this->faker();
			for ($i = 0; $i < $faker->numberBetween(1, self::AUTHORS_TO_SEED); $i++) {
				$faker = $this->faker();
				$this->createAuthor($faker->boolean(30) ? mb_strtolower($faker->name()) : $faker->name(), '/' . str_replace('-', '_', $faker->slug()));
			}

		}

		function createAuthor($fio, $link) {
			$author = new Author;
			$author->fio = $fio;
			$author->link = $link;
			$author->save();
			return $author;
		}

		function faker() {
			if (!$this->fakers) {
				foreach (['ru_RU', 'en_US', 'de_DE'] as $locale)
					$this->fakers[] = Faker\Factory::create($locale);

				$this->fakers = Illuminate\Support\Collection::make($this->fakers);
			}

			return $this->fakers->random();
		}

	}
