<?php

Admin::menu()->url('/')
	->label('Start page')->icon('fa-dashboard')
	->uses('Ankh\Http\Controllers\HomeController@getAdmin');

Admin::menu()->label('Users related')->icon('fa-user-plus')->items(function () {
	Admin::menu('User')->icon('fa-users');
	Admin::menu('Role')->icon('fa-unlock-alt');
});

Admin::menu()->label('Content')->icon('fa-file-text-o')->items(function () {
	Admin::menu('Ankh\Author')->icon('fa-user');
	Admin::menu('Ankh\Group')->icon('fa-list');
});
