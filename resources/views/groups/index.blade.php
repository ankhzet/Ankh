@extends('layouts.common')

@section('content')

	<div class="list">
		@section('letter-filter')
			@include('layouts.letter-filter')
		@show

		@include('groups.list')

	@if(!$groups->isEmpty())
		<ul>
		@foreach($groups as $g)
			<li><a href="{{ route('groups.show', $g->id) }}">{{$g->title}}</a><br/></li>
		@endforeach
		</ul>
		{!! $groups->render() !!}
	@else
		No groups.
	@endif

	</div>

@stop
