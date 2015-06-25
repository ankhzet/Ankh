<?php

	use Illuminate\Database\Seeder;

	use Ankh\Author;

	class AuthorsTableSeeder extends Seeder {

		public function run() {

			$faker = Faker\Factory::create();

			for ($i = 0; $i < $faker->numberBetween(1, 100); $i++)
				$this->createAuthor($faker->name(), '/' . str_replace('-', '_', $faker->slug()));

		}

		function createAuthor($fio, $link) {
			$author = new Author;
			$author->fio = $fio;
			$author->link = $link;
			$author->save();
			return $author;
		}

	}
