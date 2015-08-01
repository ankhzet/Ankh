@extends('layouts.common')

@section('content')

	<div class="list">
		@section('letter-filter')
			@include('layouts.letter-filter')
		@show


@if(!$authors->isEmpty())
@foreach($authors as $a)
		<div class="cnt-item">
			<div class="title">
				<span class="head"><a href="{{ route('authors.show', $a) }}">{{$a->fio}}</a></span>
				<span class="link samlib"><a href="http://samlib.ru/{{ $a->link }}">/{{$a->link}}</a></span>
			</div>
		</div>
@endforeach

		{!! $authors->render() !!}
@else
		No authors.
@endif

	</div>

@stop
