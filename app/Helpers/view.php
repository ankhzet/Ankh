<?php

function file_size($bytes, $dec = 2) {
	$bytes = intval($bytes);
	$size   = array(' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
	$factor = floor((strlen("$bytes") - 1) / 3);

	return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function diff_size($kilobytes, $dec = 0) {
	$bytes = $kilobytes * 1024;
	$size = file_size(abs($bytes), $dec);

	$size = (($bytes >= 0) ? '+' : '-') . $size;

	return $size;
}

function view_excludes($exclude) {
	$result = [];
	foreach ($exclude as $key => $value)
		if ($value !== null)
			$result[] = $key;

	return $result;
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
