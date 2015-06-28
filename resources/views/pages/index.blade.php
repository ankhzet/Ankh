@extends('layouts.common')

@section('content')

	<div class="list">
		@section('letter-filter')
			@include('layouts.letter-filter')
		@show

	@if(!$pages->isEmpty())
		<ul>
		@foreach($pages as $page)
			<li><a href="">Author</a>: <a href="{!!route('pages.show', $page->id)!!}">{{$page->title}}</a><br/></li>
		@endforeach
		</ul>
		{!! $pages->render() !!}
	@else
		No pages.
	@endif

	</div>

@stop

