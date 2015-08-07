@section('rss')@stop

@section('rss-link')
@i-menu()
<li><a href="/rss.xml?@yield('rss')">RSS</a></li>
@endmenu
@stop

@section('rss-meta')
<link href="/rss.xml?@yield('rss')" type="application/rss+xml" rel="alternate" title="RSS Feed" />
@stop
