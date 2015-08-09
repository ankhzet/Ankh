<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />

@section('meta')
		@include('meta')
@show

		<link rel="shortcut icon" href="/favicon.ico" />

		{!! asset_link('less/style.less') !!}

	</head>
	<body class="{{Request::segments(0)[0]}}-page">

	<div class="wrapper">
		<header>
			<nav id="navigation">
				<div class="menu">
					<ul>
						<li>
							<a href="{{ route('home') }}">
								<img src="{{asset('assets/img/logo.png')}}" alt="@lang('pages.home')" title="@lang('pages.home')" />
							</a>
						</li>
						<li><a href="{{ route('authors.index') }}">@lang('pages.authors.index')</a></li>
						<li><a href="{{ route('groups.index') }}">@lang('pages.groups.index')</a></li>
						<li><a href="{{ route('pages.index') }}">@lang('pages.pages.index')</a></li>
					</ul>

					<span class="user">
@if(Auth::guest())
						<a href="{{ route('login') }}">@lang('common.login')</a>
@else
@include('layouts.user-menu')
						<a href="{{ route('logout') }}">@lang('common.logout')</a>
@endif
					</span>

				</div>
			</nav>

		</header>

		<main id="content" role="main">
			<div class="content">
				{!! Breadcrumbs::renderIfExists() !!}

				<div class="title content-header">
					@yield('title')

					@yield('rss-link')
					@yield('moderation')

				</div>

@if (isset($content))
					{!!$content!!}
@else
	@yield('content')
@endif
			</div>
		</main>
	</div>

	<footer class="footer">
		<ul>
			<li><a href="{{ route('home') }}">@lang('common.site')</a> &copy; 2014 All rights reserved.</li>
		</ul>
	</footer>

	{!! asset_link('js/jquery.js') !!}
	{!! asset_link('js/common.js') !!}

	</body>
</html>
