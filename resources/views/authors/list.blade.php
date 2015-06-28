@extends('layouts.common')

@section('content')

	<div class="list">
		@if($letters)
		<div class="filters">
			filter by name: <a href="?">none</a>
			<ul class="filter">
			@foreach($letters as $letter => $count)
			@if(mb_strtolower($letter) != mb_strtolower(Request::get('letter')))
				<li><a href="?letter={{$letter}}">{{$letter}}</a> <sup>{!!$count!!}</sup></li>
			@else
				<li><span class="selected">{{$letter}}</span> <sup>{!!$count!!}</sup></li>
			@endif
			@endforeach
			</ul>
		</div>
		@endif

	@if(!$authors->isEmpty())
		<ul>
		@foreach($authors as $author)
			<li><a href="{!!action('AuthorsController@show', $author->id)!!}">{{$author->fio}}</a><br/></li>
		@endforeach
		</ul>
		{!! $authors->render() !!}
	@else
		No authors.
	@endif

	</div>

@stop
