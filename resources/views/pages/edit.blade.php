@extends('entities.edit', ['entity' => $page, 'route' => [!$page->exists ? "groups.pages.store" : "groups.pages.update", $page->group, $page]])

@section('form.content')

	<div class="option">
		<div class="label">
			{!! Form::label('author', \Lang::get('pages.authors.author') . ':') !!}
		</div>
		{!! HTML::link(route('authors.show', $page->group->author), $page->group->author->fio, ['class' => 'field label', 'target' => '_blank']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('group', \Lang::get('pages.groups.group') . ':') !!}
		</div>
		{!! HTML::link(route('groups.show', $page->group), $page->group->title, ['class' => 'field label', 'target' => '_blank']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('title', \Lang::get('pages.pages.page') . ':') !!}
		</div>
		{!! Form::text('title', $page->title, ['placeholder' => 'page title', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('link', \Lang::get('common.link') . ':') !!}
		</div>
		{!! Form::text('link', $page->link, ['placeholder' => 'page link', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('annotation', \Lang::get('common.annotation') . ':') !!}
		</div>
		{!! Form::textarea('annotation', $page->annotation, ['placeholder' => 'page annotation', 'class' => 'field']) !!}
	</div>

	<div class="option">
		<div class="label">
			{!! Form::label('size', \Lang::get('common.size') . ':') !!}
		</div>
		{!! Form::text('size', $page->size, ['placeholder' => 'page size', 'class' => 'field']) !!}
	</div>

@stop
