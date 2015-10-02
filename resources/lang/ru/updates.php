<?php

return array(
	'add' => 'добавлено в БД',
	'delete' => 'удалено из БД',
	'change' => 'изменено [:a]: :old -> :new',

	'title' => 'переименовано "{:old}" в "{:new}"',
	'annotation' => 'изменилось описание',

	'authorupdate' => array(
		'change' => array (
			'fio' => 'автор "{:old}" сменил имя на "{:new}"',
			'link' => 'страница автора перемещена в [<a href="http://samlib.ru/{:new}">{:new}</a>]',
			),
		),
	'pageupdate' => array(
		'add' => array(
			'size' => 'добавлено в БД {:delta}',
			),
		'delete' => array(
			'size' => 'удалено из БД {:delta}',
			),
		'change' => array (
			'size' => '{:delta}',
			'group_id' => 'перемещено из {:old} в {:new}',
			),
		),
	);

