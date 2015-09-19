<?php namespace Ankh\Downloadable;

use Ankh\Contracts\Downloadable\Transformable;

class Transforms {

	protected $transforms = [];
	protected $cache = [];

	function __construct() {
		$this->transforms = [
		'txt' => [1, 'plaintexter'],
		'html' => [1, 'htmlizer'],
		'zip' => [2, 'ziper'],
		'utf8' => [0, 'charset-encoder', 'utf8'],
		'win1251' => [0, 'charset-encoder', 'cp1251'],
		];

	}

	public function apply(array $transforms, Transformable $transformable) {
		$path = '';
		foreach ($transforms as $transform) {
			$key = join('.', [$path, join('.', $transform)]);

			$cached = @$this->cache[$key];

			if ($cached)
				$transformable = clone $cached;
			else {
				$transformable = app($transform[0])->apply($transformable, @$transform[1]);

				$this->cache[$key] = clone $transformable;
			}

			$path = $key;
		}

		return $transformable;
	}

	public function filterTransforms($parameters) {
		$parameters = array_values(array_filter($parameters, function ($v, $k) {
			if (preg_match('#p\d+#i', $k))
				return $v;

			return null;
		}, ARRAY_FILTER_USE_BOTH));

		return $this->mapTransforms($parameters);
	}

	public function mapTransforms(array $transforms) {
		$apply = array_filter(array_map(function ($key) {
			return ($t = @$this->transforms[$key]) ? array_merge([$key], $t) : null;
		}, $transforms));

		usort($apply, function ($a, $b) {
			return $a[1] - $b[1];
		});

		$apply = array_map(function ($v) {
			return array_slice($v, 2);
		}, $apply);

		return $apply;
	}

	public function probeSize($downloadable, $transforms) {
		$transforms = $this->mapTransforms($transforms);
		if (!$transforms)
			return 0;

		return strlen($this->apply($transforms, $downloadable));
	}

}
