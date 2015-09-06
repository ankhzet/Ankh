<?php

	use Illuminate\Http\Request;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	Route::any('/', ['uses' => 'HomeController@anyIndex', 'as' => 'home']);

	Route::controller('home', 'HomeController', [
			'anyIndex' => 'home',
			'getTermsOfUse' => 'terms-of-use',
		]);


	Route::group(['namespace' => 'Auth'], function () {
		Route::controller('auth', 'AuthController', [
			'getLogin' => 'login',
			'getLogout' => 'logout',
			]);

		Route::controller('password', 'PasswordController', [
			'getEmail' => 'password.email',
			'postEmail' => 'password.email',
			'getReset' => 'password.reset',
			'postReset' => 'password.reset',
			]);

	});

Route::group(['middleware' => 'subdomens'], function() {
	Route::resource('authors', 'AuthorsController');
	Route::group(['prefix' => 'authors/{authors}'], function () {
		Route::get('check', ['uses' => 'AuthorsController@getCheck', 'as' => 'authors.check']);
		Route::get('trace-updates', ['uses' => 'AuthorsController@getTraceUpdates', 'as' => 'authors.trace-updates']);
	});

	Route::resource('groups', 'GroupsController');

	Route::resource('pages', 'PagesController');
	Route::group(['prefix' => 'pages/{pages}'], function () {
		Route::get('versions', ['uses' => 'PagesController@getVersions', 'as' => 'pages.versions']);
		Route::get('download/{version}', ['uses' => 'PagesController@getDownload', 'as' => 'pages.download']);
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
	Route::bind('updates', function ($id) {
		return \App::make(\Ankh\Contracts\UpdateRepository::class)->find($id);
	});
	Route::bind('version', function ($date) {
		$version = new \Ankh\Version();
		$version->setTimestamp(\Carbon\Carbon::createFromFormat('d-m-Y\+H-i-s', $date));
		return $version;
	});
});

	Route::get('rss/{chanel?}/{id?}', ['uses' => 'HomeController@getRSS', 'as' => 'rss']);
