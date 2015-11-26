@section('rss')@stop

@section('rss-link')
@i-menu(icons)
@m-item(!rss, '/rss/' . $__env->yieldContent('rss') )
@endmenu
@stop

@section('rss-meta')
<link href="/rss/@yield('rss')" type="application/rss+xml" rel="alternate" title="RSS Feed" />
@stop
