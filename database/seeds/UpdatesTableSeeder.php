<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Ankh\Author;
use Ankh\Group;
use Ankh\Page;
use Ankh\Update;
use Ankh\AuthorUpdate;
use Ankh\GroupUpdate;
use Ankh\PageUpdate;

class UpdatesTableSeeder extends CommonSeeder {
	const UPDATES_TO_SEED = 400;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('entity_update')->truncate();
		Update::all()->each(function ($u) { $u->forceDelete(); });

		$this->commonSeeder(Author::all(), function ($data) {
			return new AuthorUpdate($data);
		});

		$this->commonSeeder(Group::all(), function ($data) {
			return new GroupUpdate($data);
		});

		$this->commonSeeder(Page::all(), function ($data) {
			return new PageUpdate($data);
		});

	}

	public function commonSeeder($entities, $instance) {
		$this->iterate(self::UPDATES_TO_SEED / 2, self::UPDATES_TO_SEED, function ($i) use ($entities, $instance) {
			$faker = $this->faker();

			$update = $instance([
				'type' => rand(1, 6),
				'change' => Str::limit(join(' ', $faker->sentences(5)), 250),
				'value' => rand(1, 9999),
				'delta' => rand(0, 500) - 250,
				]);

			$update->created_at = $this->randomDayInPast();
			$update->save();
			$entities->random()->attachUpdate($update);
		});
	}

	public function randomDayInPast() {
		$now = \Carbon\Carbon::now();
		$now->day -= rand(0, 30 * 3);
		return $now;
	}

}
