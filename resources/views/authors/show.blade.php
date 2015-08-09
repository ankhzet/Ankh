@extends('layouts.common')

@section('title')<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a>@stop
@section('title-plain'){{$author->fio}}@stop
@section('rss')author={{$author->id}}@stop

@section('moderation')
@admin()
	@i-menu(admin )
		@m-item('common.edit', route('authors.edit', $author) )
		@m-delete('common.delete', route('authors.destroy', $author) )
	@endmenu
@endadmin
@stop

@section('content')

				<div class="cnt-item author">
					<div class="title">
						<span class="head">

							@i-menu()
								@m-item('pages.pages.list', route('authors.pages.index', $author))
								@m-item('pages.updates.chronology', route('authors.chronology', $author))
								@m-item('pages.updates.check', route('authors.check', $author))
							@endmenu
						</span>
						<span class="link date">{{$author->updated_at->ago()}}</span>
						@samlib($author)
					</div>

					<br/><br/>
				</div>

				<div class="groups">
					@include('groups.list', ['groups' => $author->groups()->paginate(10)])
				</div>
@stop
