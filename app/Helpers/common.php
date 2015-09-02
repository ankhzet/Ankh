<?php

function pick_arg() {
	$stack = debug_backtrace(0, 2);
	$classes = $stack[0]['args'];

	$vars = [];
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
