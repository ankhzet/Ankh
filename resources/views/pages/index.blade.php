@extends('layouts.common')

@section('content')

	@include('layouts.filters')
	@include('layouts.list', ['name' => 'page'])

@stop
