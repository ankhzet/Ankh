<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;

	class AuthorsTableSeeder extends CommonSeeder {
		protected $locales = ['ru_RU', 'en_US', 'de_DE'];
		const AUTHORS_TO_SEED = 50;

		public function run() {
			$this->iterate(self::AUTHORS_TO_SEED / 2, self::AUTHORS_TO_SEED, function () {
				$faker = $this->faker();
				$this->createAuthor($faker->boolean(30) ? mb_strtolower($faker->name()) : $faker->name(), '/' . str_replace('-', '_', $faker->slug()));
			});

		}

		function createAuthor($fio, $link) {
			$author = new Author;
			$author->fio = $fio;
			$author->link = $link;
			$author->save();
			return $author;
		}

	}
