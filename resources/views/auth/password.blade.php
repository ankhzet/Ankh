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

{!! Form::open(['route' => 'password.email', 'method' => 'post'] ) !!}

<div class="password-reset">
	<div class="header">
		<div>
			{{ trans('common.user-reset-password') }}
		</div>
	</div>

	<div class="option {{isOk('email')}}">
		<div class="label">{!! Form::label('email', trans('common.email') . ':') !!}</div>
		{!! Form::email('email', old('email'), ['class' => 'field']) !!}
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

<?php
	if ($status = session('status'))
		session('message', trans($status));
?>

@stop
