<?php

function file_size($bytes, $dec = 2) {
	$bytes = intval($bytes);
	$size   = array(' B', ' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
	$factor = floor((strlen("$bytes") - 1) / 3);

	return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
