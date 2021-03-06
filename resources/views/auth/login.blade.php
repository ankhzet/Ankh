@extends('layouts.common')

@section('content')

<?php
	global $e;
	$e = $errors;
	function isOk($field) {
		global $e;
		return $e->has($field) ? 'error' : '';
	}
?>

{!! Form::open(['route' => 'login', 'method' => 'post'] ) !!}

<div class="login">
	<div class="header">
		<div>
			{{ trans('common.user-login') }}
		</div>
	</div>

	<div class="option {{isOk('email')}}">
		<div class="label">{!! Form::label('email', trans('common.email') . ':') !!}</div>
		{!! Form::email('email', old('email'), ['class' => 'field']) !!}
	</div>

	<div class="option {{isOk('password')}}">
		<div class="label">{!! Form::label('password', trans('common.password') . ':') !!}</div>
		{!! Form::password('password', ['class' => 'field']) !!}
	</div>

	<div class="option {{isOk('remember')}}">
		<div class="label">{!! Form::label('remember', trans('common.remember') . ':') !!}</div>
		{!! Form::checkbox('remember', 1, old('remember'), ['class' => 'field']) !!}
	</div>


	<div class="option">
@foreach ($errors->all() as $key => $error)
		<span class="field error">{{ $error }}</span>
@endforeach
	</div>

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! Form::submit(trans('common.login')) !!}

		<a href="{{ route('password.email') }}" class="right">{!! trans('common.forgot-password') !!}</a>
	</div>

</div>

{!! Form::close() !!}

@stop
