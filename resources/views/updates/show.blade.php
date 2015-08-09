@extends('layouts.common')

@section('title')<a href="{{ route('updates.show', $update) }}">{{(string)$update}}</a>
@stop
@section('title-plain'){{(string)$update}}@stop

@section('moderation')
@admin()
@i-menu(admin)
	@m-item('common.edit', route('updates.edit', $group) )
	@m-delete('common.delete', route('updates.destroy', $group) )
@endmenu
@endadmin
@stop

@section('content')

				<div class="cnt-item page">
					<div class="title">
						<span class="head">
							@i-menu()
								@m-item('pages.updates.chronology', route('updates.show', $update) )
							@endmenu
						</span>
						<span class="link date">{{$update->created_at->ago()}}</span>
					</div>
				</div>

@stop
