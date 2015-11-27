@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a>@stop
@section('title-plain'){{$author->fio}}@stop
@section('rss')author/{{$author->id}}@stop

@section('moderation')
@admin()
	@i-menu(admin icons)
		@m-item(!edit, route('authors.edit', $author) )
		@m-item(!check, route('authors.check', $author))
		@m-delete(!trash, route('authors.destroy', $author) )
	@endmenu
@endadmin
@i-menu(right)
	@m-item(!list, route('authors.pages.index', $author))
	@m-item('pages.updates.chronology', route('authors.updates.index', $author))
	@m-item('pages.authors.trace-updates', route('authors.trace-updates', $author) )
@endmenu
@stop

@section('content')

				<div class="cnt-item @trashed($author) author">
					<div class="title">
						<span class="head">
						</span>
							<span class="link date">{{$author->updated_at->ago()}}</span>
							@samlib($author)
					</div>
					<div class="text">
						<div class="author-title">{{ $author->title }}</div>
						<small>
							@lang('pages.authors.rating'): {{ $author->rating }}<br/>
							@lang('pages.authors.visitors'): {{ $author->visitors }}
						</small>
					</div>
				</div>

				<div class="groups">
					@include('layouts.list', ['name' => 'group', 'groups' => $author->peekGroups($delta, 10, true), 'exclude' => ['author']])
				</div>
@stop
