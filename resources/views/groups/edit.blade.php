@extends('layouts.common')

@section('content')

{!! Form::model($group, ['route' => ['groups.update', $group], 'method' => 'patch'] ) !!}
{{-- {!! Form::hidden('_method', 'patch') !!} --}}
<div class="edit">
	<div class="header">
		<span style="position: absolute; float: right; color: gray;">
			{!! Form::checkbox('deleted', 1, $group->deleted_at !== null, ['class' => 'field', 'id' => 'deleted']) !!}
			{!! Form::label('deleted', \Lang::get('common.deleted')) !!}
		</span>
		<div>
			@lang('common.edit'): {{$group->title}}
		</div>
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('author', \Lang::get('pages.authors.author') . ':') !!}
		</div>
		{!! Form::label('', $group->author->fio, ['class' => 'field label']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('title', \Lang::get('pages.groups.group') . ':') !!}
		</div>
		{!! Form::text('title', null, ['placeholder' => 'group title', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">{!! Form::label(\Lang::get('common.created-updated') . ':') !!}</div>
		{!! Form::input('datetime', 'updated_at', null, ['class' => 'field half']) !!}
		{!! Form::input('datetime', 'created_at', null, ['disabled', 'class' => 'field half']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('link', \Lang::get('common.link') . ':') !!}
		</div>
		{!! Form::text('link', null, ['placeholder' => 'group link', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('annotation', \Lang::get('common.annotation') . ':') !!}
		</div>
		{!! Form::textarea('annotation', null, ['placeholder' => 'group annotation', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('inline', \Lang::get('common.inline') . ':') !!}
		</div>
		{!! Form::checkbox('inline', null, ['class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">&nbsp;</div>
		{!! Form::submit() !!}
	</div>

</div>
{!! Form::close() !!}

@stop
