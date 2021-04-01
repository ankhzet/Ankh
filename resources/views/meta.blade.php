@section('title'){{ common_title() }}@stop
@section('title-plain'){{ common_title() }}@stop

		<title>@yield('title-plain') - @lang('common.site')</title>

		<meta name="server-time" content="{{date('r')}}" />
		<meta name="generator" content="Laravel framework" />
		<meta name="_token" content="{!!csrf_token()!!}" />
@section('additional-meta')@stop
@include('rss')
		@yield('rss-meta')
