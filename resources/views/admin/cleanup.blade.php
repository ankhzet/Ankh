@extends('layouts.no-rss')

@section('moderation')
@stop

@section('form.title')
@yield('title')
@stop

@section('content')

{!! Form::open( ['method' => 'post'] ) !!}

<div class="edit">
	<div class="header">
		<div>
			{{common_title()}}
		</div>
	</div>

@yield('form.content')

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! HTML::link(route('admin.cleanup.db'), 'Cleanup deleted pages', ['class' => 'field label', 'target' => '_blank']) !!}
	</div>

@if($statistics)
	<div class="option">
	{!! dump($statistics) !!}
	</div>
@endif

	<div class="option">
@foreach ($errors->all() as $key => $error)
		<span class="field error">{{ $error }}</span>
@endforeach
	</div>

</div>
{!! Form::close() !!}


@stop
