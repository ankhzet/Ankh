@extends('layouts.common')

@section('content')
	<h3>Whoops!</h3>

	<div>{!!Request::url()!!}</div>
	<div>
		Sorry, requested content not found =(
	</div>
@stop