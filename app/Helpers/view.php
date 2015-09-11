<?php

function file_size($bytes, $dec = 2, $truncate = true) {
	$bytes = intval($bytes);
	$size   = array(' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
	$factor = floor((strlen("$bytes") - 1) / 3);

	$float = $bytes / pow(1024, $factor);
	$str = sprintf("%.{$dec}f", $float);
	if ($truncate && strpos($str, '.') !== false) {
		$s = explode('.', $str);
		if (trim($s[1], '0') == '')
			$str = $s[0];
	}

	return $str . @$size[$factor];
}

function diff_size($bytes, $dec = 0, $plus = '+') {
	$size = file_size(abs($bytes), $dec);

	$size = (($bytes >= 0) ? $plus : '-') . $size;

	return $size;
}

function view_excludes($exclude) {
	$result = [];
	foreach ($exclude as $key => $value)
		if ($value !== null)
			$result[] = $key;

	return $result;
}

function uri_slug() {
	$segments = Request::segments(0);
	if (isset($segments[0]))
		return $segments[0];

	return 'home';
}

function common_title() {
	$picked = null;
	$route = Request::route();
	if ($route) {
		$routeName = $route->getName();
		$page = Lang::get("pages.{$routeName}");
		if ($page != $routeName)
			$picked = $page;
	}

	if ($picked == null)
		$picked = Lang::get('pages.home');

	return is_array($picked) ? @$picked['index'] ?: first($picked) : $picked;
}
