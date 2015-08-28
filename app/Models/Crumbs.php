<?php namespace Ankh;

use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Breadcrumbs;

class Crumbs {
	const HOME_ROUTE = 'home';

	protected $route = null;
	protected $routes = null;
	protected $crumbs = [];

	public function __construct(Router $routes, Route $route) {
		$this->route = $route;
		$this->routes = $routes;

		$this->parseRoute($route);
	}

	function parseRoute(Route $route) {
		$this->pushBreadcrumb(self::HOME_ROUTE);
		$routeName = $route->getName();

		Breadcrumbs::register($routeName, function($breadcrumbs) use ($routeName) {
			$specifiers = func_get_args();
			array_shift($specifiers);
			$specific = [];

			$parts = explode('.', $routeName);

			if (last($parts) == 'edit') {
				array_pop($parts);
				array_push($parts, '!');
				array_push($parts, 'edit');
			}

			$parent = null;
			while ($part = array_shift($parts)) {

				if (strpos($parent, '.index') !== false)
					$parentRoute = str_replace('.index', '', $parent);
				else
					$parentRoute = $parent;

				$current = $parent ? "{$parentRoute}.{$part}" : "{$part}.index";

				$resolved = $this->resolveRoute($current, $part);
				$route = explode('.', $resolved);
				$part = array_pop($route);

				$specifier = null;
				switch ($part) {
					case 'show':
					case 'edit':
						if (!$this->lastIs('index'))
							$this->pushBreadcrumb($this->traversed($resolved, 'index'), $specific);
					case 'create':
					case 'delete':
						$specifier = array_shift($specifiers);
						$specific[] = $specifier;
					default:
					break;
				}

				if ($this->routeExists($resolved))
					$this->pushBreadcrumb($resolved, $specific);
				else
					throw new \Exception("Route [{$current}] don't exists");

				$parent = $current;
			}

			$unique = [];
			$parent = null;
			foreach ($this->crumbs as $route) {
				if (array_search($route[0], $unique) !== false)
					continue;

				$this->pushRoute($breadcrumbs, $parent, $route[0], $route[1]);
				$unique[] = $parent = $route[0];
			}

		});
	}

	function pushRoute($breadcrumbs, $parent, $current, $specific) {

		$resolvedParent = $this->resolveRoute($parent, $current);

		if (($resolvedParent != $current) && Breadcrumbs::exists($resolvedParent))
			$breadcrumbs->parent($resolvedParent);

		if ($this->routeExists($current)) {
			$label = $this->breadcrumbLabel($current, last($specific));

			$route = route($current, array_filter($specific));
			$breadcrumbs->push($label, $route);
		} else
			throw new \Exception("Route [{$current}] don't exists");
	}

	function pushBreadcrumb($route, $specifier = []) {
		if ($this->lastPushedRoute() != $route)
			$this->crumbs[] = [$route, $specifier];
	}

	function lastPushedBreadcrumb() {
		if (!($count = count($this->crumbs)))
			return null;

		return $this->crumbs[$count - 1];
	}

	function lastPushedRoute() {
		return $this->lastPushedBreadcrumb()[0];
	}

	function lastIs($actions) {
		$actions = is_array($actions) ? $actions : func_get_args();
		foreach ($actions as $arg) {
			$action = ".{$arg}";
			if (substr($this->lastPushedRoute(), -strlen($action)) == $action)
				return $arg;
		}

		return false;
	}

	function traversed($route, $action = 'index') {
		$parts = explode('.', $route);
		array_pop($parts);
		array_push($parts, $action);
		return join($parts, '.');
	}

	function routeExists($routeName) {
		return $this->routes->has($routeName);
	}

	function resolveRoute($route, $part) {
		$solved = $route ?: static::HOME_ROUTE;

		if (!$this->routeExists($solved)) {
			$traverse = ['index' => 'show', 'show' => 'edit'];
			if ($t = $this->lastIs(array_keys($traverse)))
				$solved = $this->traversed($this->lastPushedRoute(), @$traverse[$t] ?: 'show');
			else
				$solved = "{$solved}.index";
		}

		if (!$this->routeExists($solved))
			throw new \Exception("Route [{$route}] don't exists");

		return $solved;
	}

	function breadcrumbLabel($route, $specifier = null) {
		$label = \Lang::get("pages.{$route}");

		while (is_array($label))
			$label = $label[array_key_exists('index', $label) ? 'index' : array_keys($label)[0]];

		if (!$specifier)
			return $label;

		if (!is_object($specifier))
			return $specifier;

		return preg_replace_callback('/\{:([\w_][\w\d_]*)\}/i', function ($match) use ($specifier) {
			$method = $match[1];
			// $method = studly_case($match[1]);
			// $method = "getAttribute({$method})";
			return $specifier->getAttribute($method);
		}, $label);
	}
}
