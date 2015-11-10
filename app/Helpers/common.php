<?php

use Illuminate\Support\Str;

function pick_arg($variables, $class = null) {
	$stack = debug_backtrace(0, 2);
	$classes = $stack[0]['args'];

	$vars = [];

	if (is_array($classes[0]))
		$args = array_shift($classes);
	else
		$args = array_reverse($stack[1]['args']);

	foreach ($classes as $index => $class) {
		foreach ($args as $arg)
			if (is_object($arg) && ($arg instanceof $class)) {
				$vars[$index] = $arg;
				break;
			}

		if (!isset($vars[$index]))
			$vars[$index] = null;
	}

	return (count($classes) > 1) ? $vars : last($vars);
}

function strip_unwanted_tags($text, $tags = []) {
	foreach ($tags as $tag) {
		if (preg_match_all('/<'.$tag.'[^>]*>(.*)<\/'.$tag.'>/iU', $text, $found)) {
			$text = str_replace($found[0], $found[1], $text);
		}

		if (preg_match_all('/<'.$tag.'[^>]*>/iU', $text, $found))
			$text = str_replace($found[0], '', $text);
	}

	return $text;

}

function path_join() {
	$args = func_get_args();
	$r = [];
	foreach ($args as $portion)
		$r[] = rtrim($portion, '/\\');

	return preg_replace('#[\\/]+#', '/', join('/', $r));
}

function krsort_tree(&$array) {
	foreach ($array as $key => &$value)
		if (is_array($value))
			krsort_tree($value);

	krsort($array);
}

function array_append_recursive($a1, $a2) {
	foreach ($a2 as $key => $value)
		if (is_array($value))
			$a1[$key] = array_append_recursive(@$a1[$key] ?: [], $value);
		else
			$a1[$key] = $value;

	return $a1;
}

function hasUpdateModifier($modifier, $where) {
	return strpos($where, $modifier) !== false;
}
function pickUpdateModifiers(&$field) {
	$old = $field;
	return str_replace($field = clearUpdateField($field), '', $old);
}
function clearUpdateField($field) {
	return ltrim($field, '-*');
}
