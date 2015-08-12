@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a> - <a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>@stop
@section('title-plain'){{$author->fio}}. {{$group->title}}: {{$page->title}}@stop
@section('rss')page/{{$page->id}}@stop

@section('moderation')
@admin()
  @i-menu(admin)
    @m-item('common.edit', route('pages.edit', $page) )
    @m-delete('common.delete', route('pages.destroy', $page) )
  @endmenu
@endadmin
@stop

@section('reader')
{!!$reader!!}
@stop

@section('content')


<div class="page">
  <div class="text reader">
    <div class="pre">
      @yield('reader')
    </div>
    <div class="terminator"></div>
  </div>
</div>


@stop

