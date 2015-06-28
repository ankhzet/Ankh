<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />

		<title>{{$page_title or Lang::get('common.homepage')}} - @lang('common.site')</title>

		{!! asset_link('less/style.less') !!}

	</head>
	<body class="{{Request::segments(0)[0]}}-page">
			{!! Breadcrumbs::renderIfExists() !!}


		<div id="main-container">
			@if (isset($content))
				{{$content}}
			@else
				@yield('content')
			@endif

			<footer>
				<ul>
					<li><a href="{!!URL::to('/')!!}">Ankhzet</a> &copy; 2014 All rights reserved.</li>
				</ul>
			</footer>
		</div>
	</body>
</html>
