@extends('entities.edit', ['entity' => $author, 'route' => [!$author->exists ? "authors.store" : "authors.update", $author]])

@section('form.title')
{{ common_title() }}@if($author->exists): {{$author->fio}}@endif
@stop

@section('form.content')

@if($author->exists)
	<div class="option">
		<div class="label">
			{!! Form::label('fio', \Lang::get('pages.authors.author') . ':') !!}
		</div>
		{!! Form::text('fio', $author->fio, ['placeholder' => 'author fio', 'class' => 'field']) !!}
	</div>
@endif

	<div class="option">
		<div class="label">
			{!! Form::label('link', \Lang::get('common.link') . ':') !!}
		</div>
		{!! Form::text('link', $author->link, ['placeholder' => 'author link', 'class' => 'field']) !!}
	</div>

@stop
