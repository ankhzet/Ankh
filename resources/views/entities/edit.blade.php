@extends('layouts.common')

@section('form.title')
{{ common_title() }}@if($entity->exists): {{$entity->title}}@endif
@stop

@section('content')

{!! Form::model($entity, ['route' => $route, 'method' => $entity->exists ? 'patch' : 'post'] ) !!}

<div class="edit">
	<div class="header">
		<div>
			@yield('form.title')
		</div>
	</div>

@yield('form.content')

@if($entity->exists)
	<div class="option">
		<div class="label">{!! Form::label(\Lang::get('common.created-updated') . ':') !!}</div>
		{!! Form::input('datetime-local', 'updated_at', $entity->updated_at->format('Y-m-d\TH:i'), ['class' => 'field half']) !!}
		{!! Form::input('datetime-local', 'created_at', $entity->created_at->format('Y-m-d\TH:i'), ['disabled', 'class' => 'field half']) !!}
	</div>
@endif

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! Form::submit() !!}
@admin()
		<span class="right" style="color: gray;">
			{!! Form::checkbox('deleted', 1, $entity->deleted_at !== null, ['class' => 'field', 'id' => 'deleted']) !!}
			{!! Form::label('deleted', \Lang::get('common.deleted')) !!}
		</span>
@endadmin
	</div>

	<div class="option">
@foreach ($errors->all() as $key => $error)
		<span class="field error">{{ $error }}</span>
@endforeach
	</div>

</div>
{!! Form::close() !!}

@stop
