<?php

use Jenssegers\Date\Date;

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

function date_ago($date) {
	switch ($date->diff(today())->days) {
	case 0:
		return \Lang::get('pages.updates.today');
		break;
	case 1:
		return \Lang::get('pages.updates.yesterday');
		break;
	}

	return $date->ago();
}

function today() {
	global $today;

	if (!isset($today)) {
		$today = Date::now();
		$today->hour = 0;
		$today->minute = 0;
		$today->second = 0;
	}
	return $today;
}

function cleanup_annotation($html) {
	$html = str_replace("\n", "", $html);
	$html = preg_replace('"<(br|dd|p)[\s/]*>"', "\n", $html);
	$html = strip_unwanted_tags($html, ['font', 'dd']);
	$html = trim($html);
	$html = str_replace("\n", "<br/>", $html);
	return $html;
}
