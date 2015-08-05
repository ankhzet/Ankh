@extends('layouts.common')

@section('title')<a href="/authors/{{$author->id}}">{{$author->fio}}</a>. <a href="/groups/{{$group->id}}">{{$group->title}}</a>: <a href="/pages/{{$page->id}}">{{$page->title}}</a>@stop
@section('title-plain'){{$author->fio}}. {{$group->title}}: {{$page->title}}@stop
@section('rss')page={{$page->id}}@stop

@section('moderation')
<span class="inline-menu admin">
  <a href="/pages/{{$page->id}}/edit">@lang('common.edit')</a> | <a href="/pages/{{$page->id}}/destroy">@lang('common.delete')</a>
</span>
@stop

@section('reader')
Reader: {!!$reader!!}
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

