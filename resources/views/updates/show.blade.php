@extends('layouts.common')

@section('title')<a href="{{ route('updates.show', $update) }}">{{(string)$update}}</a>
@stop
@section('title-plain'){{(string)$update}}@stop

@section('moderation')
@admin()
@i-menu(admin icons)
	@m-delete(!trash, route('updates.destroy', $update) )
@endmenu
@endadmin
@stop

@section('content')

				<div class="cnt-item page">
					<div class="title">
						{!! $update !!}
						<span class="link date">{{with($update->created_at ?: \Date::now())->ago()}}</span>
					</div>
				</div>

@stop
