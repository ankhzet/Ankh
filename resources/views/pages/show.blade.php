@extends('layouts.common')

@section('title')
    @kept(author)<a href="{{ route('authors.show', $page->author) }}">{{$page->author->fio}}</a> - @endkept<a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>
    <span class="pull-right">
        <a id="night-mode-toggle" href="javascript:void(0)">Night mode</a>
    </span>
@stop
@section('title-plain'){{$page->author->fio}}. {{$page->group->title}}: {{$page->title}}@stop
@section('rss')page/{{$page->id}}@stop

@section('moderation')
@admin()
  @i-menu(admin icons right)
    @m-item(!edit, route('pages.edit', $page) )
    @m-delete(!trash, route('pages.destroy', $page) )
  @endmenu
@endadmin
@stop

@section('content')

<div class="page">
  <div class="text reader">
    <div class="pre">
{!!$text!!}
    </div>
    <div class="terminator"></div>
  </div>
</div>


@stop

