<?php

$updates = array(
	'index' => 'Updates',

	'chronology' => 'chronology',
	'check' => 'check new',
	);

$pages = array(
		'index' => 'Pages',
		'show' => '{:title}',

		'updates' => $updates,

		'list' => 'list pages',
	);

$groups = array(
		'index' => 'Groups',
		'show' => '{:title}',

		'pages' => $pages,
		'updates' => $updates,
	);

$authors = array(
	'index' => 'Authors',
	'show' => '{:fio}',
	'create' => 'Add',
	'edit' => 'Edit',

	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,
	);

return array(
	'home' => 'Home',

	'authors' => $authors,
	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,

	'user' => array(
		'profile' => 'Profile',
		),

	);
