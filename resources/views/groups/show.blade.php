@extends('layouts.common')

@section('title') {{$group->fio}} @stop

@section('content')
	<div class="group">
		{{$group->title}} <span class="right">{{$group->link}}</span>
	</div>
@stop
