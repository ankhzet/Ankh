<?php

	Route::any('/', function() { return Redirect::to('/home'); });

	Route::controller('home', 'HomeController', ['anyIndex' => 'home']);

	Route::controller('password', 'Auth\PasswordController');

	Route::group(['prefix' => 'password', 'namespace' => 'Auth'], function () {
		Route::get('email', ['uses' => 'PasswordController@getEmail', 'as' => 'password.email']);
		Route::post('email', ['uses' => 'PasswordController@postEmail', 'as' => 'password.email']);
		Route::get('reset/{token?}', ['uses' => 'PasswordController@getReset', 'as' => 'password.reset']);
	});

	Route::controller('auth', 'Auth\AuthController', [
			'getLogin' => 'login',
			'getLogout' => 'logout',
		]);

	Route::get('/auth', function() {
		return Redirect::to('/user');
	});


	Route::resource('authors', 'AuthorsController');
	Route::resource('groups', 'GroupsController');
	Route::resource('pages', 'PagesController');

	Route::resource('authors.groups', 'GroupsController');
	Route::resource('authors.pages', 'PagesController');
	Route::resource('groups.pages', 'PagesController');

	Route::bind('authors', function ($id) {
		return Ankh\Author::findOrFail($id);
	});
	Route::bind('groups', function ($id) {
		return Ankh\Group::findOrFail($id);
	});
	Route::bind('pages', function ($id) {
		return Ankh\Page::findOrFail($id);
	});
