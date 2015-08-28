@extends('layouts.common')

@section('moderation')
@admin()
	@i-menu(admin )
		@m-item('common.add', route('authors.create') )
	@endmenu
@endadmin
@stop

@section('content')

	@include('layouts.filters')
	@include('authors.list')

@stop
