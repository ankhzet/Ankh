<?php

	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	use Ankh\Contracts\AuthorRepository;
	use Ankh\Contracts\GroupRepository;
	use Ankh\Contracts\PageRepository;
	use Ankh\Contracts\UpdateRepository;
	use Ankh\Version;

	Route::controller('home', 'HomeController', [
			'getTermsOfUse' => 'terms-of-use',
		]);

	Route::any('/', ['uses' => 'HomeController@anyIndex', 'as' => 'home']);

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
	Route::get('authors/check', ['middleware' => ['admin'], 'uses' => 'AuthorsController@getCheck', 'as' => 'authors.check']);
	Route::resource('authors', 'AuthorsController');
	Route::group(['prefix' => 'authors/{authors}'], function () {
		Route::get('trace-updates', ['uses' => 'AuthorsController@getTraceUpdates', 'as' => 'authors.trace-updates']);
	});

	Route::resource('groups', 'GroupsController');

	Route::resource('pages', 'PagesController');
	Route::group(['prefix' => 'pages/{pages}/{version?}'], function () {
		Route::get('/', ['uses' => 'PagesController@show', 'as' => 'pages.show']);
		Route::get('versions', ['uses' => 'PagesController@getVersions', 'as' => 'pages.versions']);
		Route::get('download/{p1?}/{p2?}/{p3?}/{p4?}', ['uses' => 'PagesController@getDownload', 'as' => 'pages.download']);
	});

	Route::resource('updates', 'UpdatesController');

	Route::resource('authors.groups', 'GroupsController');
	Route::resource('authors.pages', 'PagesController');
	Route::resource('groups.pages', 'PagesController');
	Route::resource('authors.updates', 'UpdatesController');
	Route::resource('groups.updates', 'UpdatesController');
	Route::resource('pages.updates', 'UpdatesController');

	Route::bind('authors', function ($id) {
		return App::make(AuthorRepository::class)->find($id);
	});
	Route::bind('groups', function ($id) {
		return App::make(GroupRepository::class)->find($id);
	});
	Route::bind('pages', function ($id) {
		return App::make(PageRepository::class)->find($id);
	});
	Route::bind('updates', function ($id) {
		return App::make(UpdateRepository::class)->find($id);
	});
	Route::bind('version', function ($date, $route) {
		return (new Version($date))->setEntity($route->parameter('pages'));
	});
});

	Route::get('rss/{chanel?}/{id?}', ['uses' => 'HomeController@getRSS', 'as' => 'rss']);
