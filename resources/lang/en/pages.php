<?php

$updates = array(
	'index' => 'Updates',

	'chronology' => 'chronology',
	'check' => 'check new',
	'today' => 'today',
	'yesterday' => 'yesterday',
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
	'author' => 'Author',
	'create' => 'Add',
	'edit' => 'Edit',
	'trace-updates' => 'trace updates',
	'details' => 'details',

	'cant-parse-link' => 'Can\'t parse link',
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

	'rss' => array(
		'description' => 'Ankh RSS feeder',

		'updates' => 'Updates',
		'author' => 'Updates of author &amp;laquo;:param&amp;raquo;',
		'group' => 'Updates of group &amp;laquo;:param&amp;raquo;',
		'page' => 'Updates of page &amp;laquo;:param&amp;raquo;',
		),

	);
