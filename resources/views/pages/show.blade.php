@extends('layouts.common')

@section('reader')
Reader: {!!$reader!!}
@stop

@section('content')
	<div class="page">
		<div class="text reader">
			<div class="pre">
@yield('reader')
			</div>
			<div class="terminator"></div>
		</div>
	</div>
@stop

