@section('rss')@stop
@section('rss-link')
<span class="inline-menu right"><a href="/rss.xml?@yield('rss')">RSS</a></span>
@stop
@section('rss-meta')
<link href="/rss.xml?@yield('rss')" type="application/rss+xml" rel="alternate" title="RSS Feed">
@stop
