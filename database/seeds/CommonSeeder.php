<?php

	use Illuminate\Database\Seeder;

	class CommonSeeder extends Seeder {
		private $fakers = null;
		protected $locales = ['ru_RU'];

		public function run() {

		}

		public function iterate($min, $max, Closure $closure) {
			$to = mt_rand($min, $max);
			for ($i = 0; $i < $to; $i++)
				$closure($i);
		}


		function faker() {
			$single = count($this->locales) < 2;

			if (!$this->fakers) {
				if (!$this->locales)
					$this->locales = [env('locale')];

				if ($single)
					$this->fakers = Faker\Factory::create(array_values($this->locales)[0]);
				else {
					foreach ($this->locales as $locale)
						$this->fakers[] = Faker\Factory::create($locale);

					$this->fakers = Illuminate\Support\Collection::make($this->fakers);
				}
			}

			return $single ? $this->fakers : $this->fakers->random();
		}

	}
