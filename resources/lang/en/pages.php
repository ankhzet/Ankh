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
		'all-versions' => 'all versions',
		'another' => '+ another :count...',
		'no-pages' => 'No pages',
	);

$groups = array(
		'index' => 'Groups',
		'group' => 'Group',
		'show' => '{:title}',

		'pages' => $pages,
		'updates' => $updates,

		'no-groups' => 'No groups',
	);

$authors = array(
	'index' => 'Authors',
	'show' => '{:fio}',
	'create' => 'Add',
	'edit' => 'Edit',
	'trace-updates' => 'trace updates',
	'details' => 'details',

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
