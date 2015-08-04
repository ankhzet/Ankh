@section('title')@lang('pages.home')@stop
@section('title-plain')@lang('pages.home')@stop

		<title>@yield('title-plain') - @lang('common.site')</title>

		<meta name="server-time" content="{{date('r')}}" />
		<meta name="generator" content="Laravel framework" />
		<meta name="_token" content="{!!csrf_token()!!}" />
@include('rss')
		@yield('rss-meta')
