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

{!! Form::open(['route' => 'register', 'method' => 'post'] ) !!}

<div class="registraion">
	<div class="header">
		<div>
			{{ trans('common.user-registration') }}
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

	<div class="option {{isOk('name')}}">
		<div class="label">{!! Form::label('name', trans('common.user-name') . ':') !!}</div>
		{!! Form::text('name', old('name'), ['class' => 'field']) !!}
	</div>

	<div class="option {{isOk('agreed')}}">
		<div class="label">&nbsp;</div><?php Form::label('agreed') ?>
		{!! Form::checkbox('agreed', 1, old('agreed'), ['class' => 'field']) !!}
		<label for="agreed">{!! trans('common.agreed', ['terms' => trans('common.terms-of-use', ['url' => route('terms-of-use')])]) !!}</label>
	</div>

	<div class="option">
@foreach ($errors->all() as $key => $error)
		<span class="field error">{{ $error }}</span>
@endforeach
	</div>

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! Form::submit(trans('common.register')) !!}
	</div>

</div>

{!! Form::close() !!}

@stop
