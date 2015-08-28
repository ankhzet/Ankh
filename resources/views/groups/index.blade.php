@extends('layouts.common')

@section('moderation')
@if(isset($author))
@admin()
	@i-menu(admin )
		@m-item('common.add', route('authors.groups.create', $author) )
	@endmenu
@endadmin
@endif
@stop

@section('content')

	@include('layouts.filters')
	@include('groups.list')

@stop
