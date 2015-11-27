@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $group->author) }}">{{$group->author->fio}}</a> - <a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
@stop
@section('title-plain'){{$group->author->fio}} - {{$group->title}}@stop
@section('rss')group/{{$group->id}}@stop

@section('moderation')
@admin()
@i-menu(admin icons)
	@m-item(!edit, route('groups.edit', $group) )
	@m-delete(!trash, route('groups.destroy', $group) )
@endmenu
@endadmin
@i-menu(right)
	@m-item(!list, route('groups.pages.index', $group) )
	@m-item('pages.updates.chronology', route('groups.updates.index', $group) )
@endmenu
@stop

@section('content')

				<div class="cnt-item group">
					<div class="title">
						<span class="head">
						</span>
						<span class="link date">{{$group->updated_at->ago()}}</span>
						@samlib($group)
					</div>
					<div class="text quote">{!! $group->annotation !!}</div>
				</div>

				<div class="pages">
					@include('layouts.list', ['name' => 'page', 'pages' => $group->peekPages($delta, 10, true), 'exclude' => array_merge($exclude, ['group'])])
				</div>
@stop
