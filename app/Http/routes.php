<?php

	Route::any('/', function() { return Redirect::to('/home'); });

	Route::controller('home', 'HomeController');

	Route::controller('password', 'Auth\PasswordController');

	Route::get('/password/email', ['uses' => 'Auth\PasswordController@getEmail', 'as' => 'password.email']);
	Route::post('/password/email', ['uses' => 'Auth\PasswordController@postEmail', 'as' => 'password.email']);
	Route::get('/password/reset/{token}', ['uses' => 'Auth\PasswordController@getReset', 'as' => 'password.reset']);
	Route::post('/password/reset', ['uses' => 'Auth\PasswordController@postReset', 'as' => 'password.reset']);


	Route::controller('auth', 'Auth\AuthController');

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
