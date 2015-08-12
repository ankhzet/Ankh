@section('rss')@stop

@section('rss-link')
@i-menu()
<li><a href="/rss/@yield('rss')">RSS</a></li>
@endmenu
@stop

@section('rss-meta')
<link href="/rss/@yield('rss')" type="application/rss+xml" rel="alternate" title="RSS Feed" />
@stop
