<?php /** @noinspection PhpUndefinedClassInspection */

namespace Ankh;

use DaveJamesMiller\Breadcrumbs\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Breadcrumbs;

class Crumbs {
	const HOME_ROUTE = 'home';
	const INDEX_ROUTE = 'index';
	const SHOW_ROUTE = 'show';

	const ENT_DELIM = '!';

	protected $route = null;
	protected $routes = null;
	protected $crumbs = [[self::HOME_ROUTE, []]];
	protected $entitied = ['edit', 'show', 'delete', 'check', 'download', 'diff', 'read'];
	protected $sole = ['create'];

	public function __construct(Router $routes, Route $route) {
		$this->route = $route;
		$this->routes = $routes;

		$this->parseRoute($route);
	}

	function parseRoute(Route $route) {
		$routeName = $route->getName();

		Breadcrumbs::register($routeName, function($breadcrumbs, ...$specifiers) use ($routeName) {
//			var_dump(json_encode($specifiers, JSON_UNESCAPED_UNICODE));

			$parts = $this->expandRoute($routeName);
			$specific = [];
			$parent = null;

			while ($part = array_shift($parts)) {
				$parentRoute = str_replace('.' . static::INDEX_ROUTE, '', $parent);

				$current = $parent ? "{$parentRoute}.{$part}" : $part . '.' . static::INDEX_ROUTE;

				$resolved = $this->resolveRoute($current, $part);
				$route = explode('.', $resolved);
				$part = array_pop($route);

				$specifier = null;

				if (str_contains($part, $this->entitied)) {
					if (!$this->lastIs(static::INDEX_ROUTE)) {
						$this->pushBreadcrumb($this->traversed($resolved, static::INDEX_ROUTE), $specific);
					}

					$specifier = array_shift($specifiers);
					$specific[] = $specifier;
				}

				if (!$this->routeExists($resolved)) {
					throw new \Exception("Route [{$current}] don't exists");
				}

				$this->pushBreadcrumb($resolved, $specific);
				$parent = $current;
			}

			$unique = [];
			$parent = null;

			foreach ($this->crumbs as $route) {
				if (array_search($route[0], $unique) !== false) {
					continue;
				}

				$this->pushRoute($breadcrumbs, $parent, $route[0], $route[1]);
				$unique[] = $parent = $route[0];
			}
		});
	}

	function expandRoute($routeName) {
		$parts = explode('.', $routeName);

		if (str_contains(last($parts), $this->entitied)) {
			$last = array_pop($parts);

			if (array_search($last, $this->sole) !== false)
				array_push($parts, static::ENT_DELIM);
			else
				$parts = array_pad($parts, count($parts) * 2, static::ENT_DELIM);

			array_push($parts, $last);
		}

		return $parts;
	}

	function pushRoute(Generator $breadcrumbs, $parent, $current, $specific) {
		$resolvedParent = $this->resolveRoute($parent, $current);

		if (($resolvedParent != $current) && Breadcrumbs::exists($resolvedParent)) {
			$breadcrumbs->parent($resolvedParent);
		}

		if (!$this->routeExists($current)) {
			throw new \Exception("Route [{$current}] don't exists");
		}

		$entity = str_contains(last(explode('.', $current)), $this->entitied) ? last($specific) : null;
//		$entity = last($specific) ?: null;
		$label = $this->breadcrumbLabel($current, $entity);

		$route = route($current, array_filter($specific));
		$breadcrumbs->push($label, $route, ['entity' => $entity]);
	}

	function pushBreadcrumb($route, $specifier = []) {
		if ($this->lastPushedRoute() != $route) {
			$this->crumbs[] = [$route, $specifier];
		}
	}

	function lastPushedBreadcrumb() {
		return end($this->crumbs);
	}

	function lastPushedRoute() {
		return $this->lastPushedBreadcrumb()[0];
	}

	function lastIs(...$actions) {
		$last = $this->lastPushedRoute();

		foreach ($actions as $arg) {
			$action = ".{$arg}";

			if (substr($last, -strlen($action)) === $action) {
				return $arg;
			}
		}

		return false;
	}

	function traversed($route, $action = self::INDEX_ROUTE) {
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
			$traversable = array_merge($this->entitied, [static::INDEX_ROUTE]);

			if ($t = $this->lastIs(...$traversable)) {
				$traversed = ($t == static::INDEX_ROUTE) ? static::SHOW_ROUTE : $part;
				$solved = $this->traversed($this->lastPushedRoute(), $traversed);

				if (!$this->routeExists($solved)) {
					$part = ($part != static::ENT_DELIM) ? ".{$part}" : '';
					$solved = str_replace('.' . static::ENT_DELIM, $part . '.' . static::INDEX_ROUTE, $route);

					$solved = $this->resolveRoute($solved, $part);

					if (!$this->routeExists($solved)) {
						$solved = str_replace(static::ENT_DELIM, static::INDEX_ROUTE, $route);
						$solved = $this->resolveRoute($solved, $part);
					}
				}
			} else {
				$solved = $solved . '.' . static::INDEX_ROUTE;
			}

			if (!$this->routeExists($solved)) {
				throw new \Exception("Route [{$route}] don't exists ($solved -> $t)");
			}
		}

		return $solved;
	}

	function breadcrumbLabel($route, $specifier = null) {
		$label = \Lang::get("pages.{$route}");

		while (is_array($label)) {
			$label = $label[static::INDEX_ROUTE] ?? reset($label);
		}

		if (!$specifier) {
			return $label;
		}

		if (!is_object($specifier)) {
			return $specifier;
		}

		return preg_replace_callback('/\{:([\w_][\w\d_]*)\}/i', function ($match) use ($specifier) {
			return $specifier->getAttribute($match[1]);
		}, $label);
	}
}
