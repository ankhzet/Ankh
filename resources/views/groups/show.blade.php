@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a> - <a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
@stop
@section('title-plain'){{$author->fio}} - {{$group->title}}@stop
@section('rss')group={{$group->id}}@stop

@section('moderation')
@admin()
@i-menu(admin)
	@m-item('common.edit', route('groups.edit', $group) )
	@m-delete('common.delete', route('groups.destroy', $group) )
@endmenu
@endadmin
@stop

@section('content')

				<div class="author">
					<div class="cnt-item">
						<div class="title">
							<span class="head">
								@i-menu()
									@m-item('pages.updates.chronology', route('groups.chronology', $group) )
								@endmenu
							</span>
							<span class="link date">{{$author->updated_at->ago()}}</span>
							@if($group->link) @samlib($author, $group) @endif
						</div>

						<br /><br />
					</div>

					<div class="pages">
						@include('pages.list', ['pages' => $group->pages()->orderBy('title')->paginate(10), 'exclude' => ['author', 'group']])
					</div>
@stop
