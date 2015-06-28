@extends('layouts.common')

@section('content')

	<div class="list">
		@section('letter-filter')
			@include('layouts.letter-filter')
		@show


	@if(!$authors->isEmpty())
		<ul>
		@foreach($authors as $a)
			<li><a href="{{ route('authors.show', $a->id) }}">{{$a->fio}}</a><br/></li>
		@endforeach
		</ul>
		{!! $authors->render() !!}
	@else
		No authors.
	@endif

	</div>

@stop
