@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a> - <a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
@stop
@section('title-plain'){{$author->fio}} - {{$group->title}}@stop
@section('rss')group/{{$group->id}}@stop

@section('moderation')
@admin()
@i-menu(admin)
	@m-item('common.edit', route('groups.edit', $group) )
	@m-delete('common.delete', route('groups.destroy', $group) )
@endmenu
@endadmin
@stop

@section('content')

				<div class="cnt-item group">
					<div class="title">
						<span class="head">
							@i-menu()
								@m-item('pages.pages.list', route('groups.pages.index', $group) )
								@m-item('pages.updates.chronology', route('groups.updates.index', $group) )
							@endmenu
						</span>
						<span class="link date">{{$author->updated_at->ago()}}</span>
						@samlib($group)
					</div>

					<br /><br />
				</div>

				<div class="pages">
					@include('pages.list', ['pages' => $group->pages()->orderBy('title')->paginate(10), 'exclude' => ['author', 'group']])
				</div>
@stop
