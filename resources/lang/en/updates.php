<?php

return array(
	'add' => 'added to DB',
	'delete' => 'removed from DB',
	'change' => 'changed [:a]: :old -> :new',

	'title' => 'renamed from "{:old}" to "{:new}"',
	'annotation' => 'annotation changed',

	'authorupdate' => array(
		'change' => array (
			'fio' => 'author "{:old}" has changed name to "{:new}"',
			'link' => 'author page moved to [<a href="http://samlib.ru/{:new}">{:new}</a>]',
			),
		),
	'pageupdate' => array(
		'add' => array(
			'size' => 'added to DB {:delta}',
			),
		'delete' => array(
			'size' => 'removed from DB {:delta}',
			),
		'change' => array (
			'size' => '{:delta}',
			'group_id' => 'moved from {:old} to {:new}',
			),
		),
	);

