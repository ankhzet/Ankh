@extends('layouts.common')

@section('content')

<?php
	global $e;
	$e = $errors;
	function isOk($field) {
		global $e;
		return $e->has($field) ? ' error' : '';
	}
?>

{!! Form::open(['route' => 'password.reset', 'method' => 'post'] ) !!}

{!! Form::hidden('token', $token) !!}

<div class="registraion">
	<div class="header">
		<div>
			{{ trans('common.user-reset-password') }}
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

	<div class="option {{isOk('password_confirmation')}}">
		<div class="label">{!! Form::label('password_confirmation', trans('common.confirm-password') . ':') !!}</div>
		{!! Form::password('password_confirmation', ['class' => 'field']) !!}
	</div>

	<div class="option">
@foreach ($errors->all() as $key => $error)
		<span class="field error">{{ $error }}</span>
@endforeach
	</div>

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! Form::submit(trans('common.password-reset')) !!}
	</div>

</div>

{!! Form::close() !!}

@stop
