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
	Route::group(['prefix' => 'authors/{author}'], function () {
		Route::get('chronology', ['uses' => 'AuthorsController@getChronology', 'as' => 'authors.chronology']);
		Route::get('check', ['uses' => 'AuthorsController@getCheck', 'as' => 'authors.check']);
		Route::get('trace-updates', ['uses' => 'AuthorsController@getTraceUpdates', 'as' => 'authors.trace-updates']);
	});

	Route::resource('groups', 'GroupsController');
	Route::group(['prefix' => 'groups/{group}'], function () {
		Route::get('chronology', ['uses' => 'GroupsController@getChronology', 'as' => 'groups.chronology']);
	});

	Route::resource('pages', 'PagesController');
	Route::group(['prefix' => 'pages/{pages}'], function () {
		Route::get('versions', ['uses' => 'PagesController@getVersions', 'as' => 'pages.versions']);
	});

	Route::resource('updates', 'UpdatesController');

	Route::resource('authors.groups', 'GroupsController');
	Route::resource('authors.pages', 'PagesController');
	Route::resource('groups.pages', 'PagesController');
	Route::resource('authors.updates', 'UpdatesController');
	Route::resource('groups.updates', 'UpdatesController');
	Route::resource('pages.updates', 'UpdatesController');

	Route::bind('authors', function ($id) {
		return \App::make(\Ankh\Contracts\AuthorRepository::class)->find($id);
	});
	Route::bind('groups', function ($id) {
		return \App::make(\Ankh\Contracts\GroupRepository::class)->find($id);
	});
	Route::bind('pages', function ($id) {
		return \App::make(\Ankh\Contracts\PageRepository::class)->find($id);
	});
