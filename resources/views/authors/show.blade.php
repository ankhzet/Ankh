@extends('layouts.common')

@section('title') {{$author->fio}} @stop

@section('content')
	<div class="author">
		{{$author->fio}} <span class="right">{{$author->link}}</span>
	</div>
@stop
