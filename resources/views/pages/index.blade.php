@extends('layouts.common')

@section('content')

	<div class="list">
		@section('letter-filter')
			@include('layouts.letter-filter')
		@show

		@include('pages.list')

	</div>

@stop

