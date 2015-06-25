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
	Route::model('authors', 'Ankh\Author', function ($identifier) {
		return is_numeric($identifier)
			? Ankh\Author::find(intval($identifier))
			: Ankh\Author::where('link', strtolower("/{$identifier}"))->first();
	});
