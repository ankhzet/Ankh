@extends('entities.edit', ['entity' => $group, 'route' => [!$group->exists ? "authors.groups.store" : "authors.groups.update", $group->author, $group]])

@section('form.content')

	<div class="option">
		<div class="label">
			{!! Form::label('author', \Lang::get('pages.authors.author') . ':') !!}
		</div>
		{!! HTML::link(route('authors.show', $group->author), $group->author->fio, ['class' => 'field label', 'target' => '_blank']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('title', \Lang::get('pages.groups.group') . ':') !!}
		</div>
		{!! Form::text('title', $group->title, ['placeholder' => 'group title', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('link', \Lang::get('common.link') . ':') !!}
		</div>
		{!! Form::text('link', $group->link, ['placeholder' => 'group link', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('annotation', \Lang::get('common.annotation') . ':') !!}
		</div>
		{!! Form::textarea('annotation', $group->annotation, ['placeholder' => 'group annotation', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('inline', \Lang::get('common.inline') . ':') !!}
		</div>
		{!! Form::checkbox('inline', 1, $group->inline, ['class' => 'field']) !!}
	</div>

@stop
