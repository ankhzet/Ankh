@extends('layouts.common')

@section('title')<a href="/authors/{{$author->id}}">{{$author->fio}}</a>. <a href="/groups/{{$group->id}}">{{$group->title}}</a>: <a href="/pages/{{$page->id}}">{{$page->title}}</a>@stop
@section('title-plain'){{$author->fio}}. {{$group->title}}: {{$page->title}}@stop
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

