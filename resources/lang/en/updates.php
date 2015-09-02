<?php

return array(
	'change' => 'changed [:a]: :old -> :new',
	'annotation' => 'annotation changed',
	'authorupdate' => array(
		'change' => array (
			'fio' => 'author {:old} has changed name to "{:new}"',
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
			'title' => 'renamed from {:old} to {:new}',
			'group_id' => 'moved from {:old} to {:new}',
			),
		),
	);

