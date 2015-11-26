<?php

$updates = array(
	'index' => 'Обновления',
	'show' => 'Обновление #{:id}',

	'chronology' => 'хронология',
	'check' => 'проверить новые',
	'today' => 'сегодня',
	'yesterday' => 'вчера',

	'no-updates' => 'Нет обновлений',
	);

$pages = array(
		'index' => 'Произведения',
		'show' => '{:title}',
		'create' => 'Добавить',
		'edit' => 'Редактировать',

		'download' => array (
			'index' => 'Скачать',
			'version' => 'Версия произведения',
			'encoding' => 'Формат',
			'zipped' => 'В архиве',
			'not-zipped' => 'Не в архиве',
			),

		'downloaded' => 'Скачано с <a href=":url">Ankh</a>',
		'page' => 'Произведение',

		'updates' => $updates,

		'diff' => 'Отличия',
		'read' => 'Чтение',

		'list' => 'произведения',
		'all-versions' => 'все версии',
		'another' => '+ еще :count...',
		'no-pages' => 'Нет произведений',
	);

$groups = array(
		'index' => 'Группы',
		'group' => 'Группа',
		'create' => 'Добавить',
		'show' => '{:title}',
		'edit' => 'Редактировать',

		'pages' => $pages,
		'updates' => $updates,

		'no-groups' => 'Нет групп',
	);

$authors = array(
	'index' => 'Авторы',
	'show' => '{:fio}',
	'author' => 'Автор',
	'create' => 'Добавить',
	'edit' => 'Редактировать',

	'trace-updates' => 'отслеживать обновления',
	'details' => 'детали',

	'cant-parse-link' => 'Не могу распознать ссылку',
	'already-has' => 'Автор уже занесен в базу данных',

	'rating' => 'Рейтинг',
	'visitors' => 'Посетители',

	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,
	'no-authors' => 'Нет авторов',
	);

return array(
	'home' => 'Главная',

	'login' => 'Форма входа',

	'authors' => $authors,
	'groups' => $groups,
	'pages' => $pages,
	'updates' => $updates,

	'user' => array(
		'profile' => 'Профиль',
		),

	'password' => array(
		'email' => 'Форма сброса пароля',
		'reset' => 'Сброс пароля',
		),

	'rss' => array(
		'description' => 'Ankh RSS фидер',

		'updates' => 'Обновления',
		'author' => 'Обновления у автора &amp;laquo;:param&amp;raquo;',
		'group' => 'Обновления группы &amp;laquo;:param&amp;raquo;',
		'page' => 'Обновления произведения &amp;laquo;:param&amp;raquo;',
		),

	);
