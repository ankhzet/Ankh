<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />

@section('meta')
		@include('meta')
@show

		<link rel="shortcut icon" href="/favicon.ico" />

		{!! asset_link('less/style.less') !!}
		{!! asset_link('js/jquery.js') !!}

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
					{{$content}}
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

<script type="text/javascript">
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
});

(function($) {

	var laravel = {
		initialize: function() {
			this.methodLinks = $('a[data-method]');

			this.registerEvents();
		},

		registerEvents: function() {
			this.methodLinks.click(this.handleMethod);
		},

		handleMethod: function(e) {
			var link = $(this);
			var httpMethod = link.data('method').toUpperCase();
			var form;

			// If the data-method attribute is not PUT or DELETE,
			// then we don't know what to do. Just ignore.
			if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
				return;
			}

			// Allow user to optionally provide data-confirm="Are you sure?"
			if ( link.data('confirm') ) {
				if ( ! laravel.verifyConfirm(link) ) {
					return false;
				}
			}

			form = laravel.createForm(link);
			form.submit();

			e.preventDefault();
		},

		verifyConfirm: function(link) {
			return confirm(link.data('confirm'));
		},

		createForm: function(link) {
			var form =
			$('<form>', {
				'method': 'POST',
				'action': link.attr('href')
			});

			var hiddenInput =
			$('<input>', {
				'name': '_method',
				'type': 'hidden',
				'value': link.data('method').toUpperCase()
			});
			var token =
			$('<input>', {
				'name': '_token',
				'type': 'hidden',
				'value': '<?php echo csrf_token(); ?>'
			});

			return form.append(token, hiddenInput)
								 .appendTo('body');
		}
	};

	laravel.initialize();

})(jQuery);
</script>
	</body>
</html>
