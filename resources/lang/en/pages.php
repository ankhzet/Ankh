<?php

$updates = array(
	'index' => 'Updates',
	'show' => 'Update #{:id}',

	'chronology' => 'chronology',
	'check' => 'check new',
	'today' => 'today',
	'yesterday' => 'yesterday',

	'no-updates' => 'No updates',
	);

$pages = array(
		'index' => 'Pages',
		'show' => '{:title}',
		'create' => 'Add',
		'edit' => 'Edit',
		'download' => 'Download',

		'updates' => $updates,

		'list' => 'list pages',
		'all-versions' => 'all versions',
		'another' => '+ another :count...',
		'no-pages' => 'No pages',
	);

$groups = array(
		'index' => 'Groups',
		'group' => 'Group',
		'create' => 'Add',
		'show' => '{:title}',
		'edit' => 'Edit',

		'pages' => $pages,
		'updates' => $updates,

		'no-groups' => 'No groups',
	);

$authors = array(
	'index' => 'Authors',
	'show' => '{:fio}',
	'author' => 'Author',
	'create' => 'Add',
	'edit' => 'Edit',

	'trace-updates' => 'trace updates',
	'details' => 'details',

	'cant-parse-link' => 'Can\'t parse link',
	'already-has' => 'Author is already in DB',

	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,
	'no-authors' => 'No authors',
	);

return array(
	'home' => 'Home',

	'login' => 'Login form',

	'authors' => $authors,
	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,

	'user' => array(
		'profile' => 'Profile',
		),

	'password' => array(
		'email' => 'Pasword resetting form',
		'reset' => 'Resetting pasword',
		),

	'rss' => array(
		'description' => 'Ankh RSS feeder',

		'updates' => 'Updates',
		'author' => 'Updates of author &amp;laquo;:param&amp;raquo;',
		'group' => 'Updates of group &amp;laquo;:param&amp;raquo;',
		'page' => 'Updates of page &amp;laquo;:param&amp;raquo;',
		),

	);
