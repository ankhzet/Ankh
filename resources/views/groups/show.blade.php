@extends('layouts.common')

@section('title')<a href="/authors/{{$author->id}}">{{$author->fio}}</a> - <a href="/groups/{{$group->id}}">{{$group->title}}</a>@stop
@section('title-plain'){{$author->fio}} - {{$group->title}}@stop
@section('rss')group={{$group->id}}@stop

@section('moderation')
<span class="inline-menu admin">
	<a href="/groups/{{$group->id}}/edit">@lang('common.edit')</a> | <a href="/groups/{{$group->id}}/delete">@lang('common.delete')</a>
</span>
@stop

@section('content')

				<div class="author">
					<div class="cnt-item">
						<div class="title">
							<span class="head">
								<span class="pull_right inline-menu">
									<a href="/groups/{{$group->id}}/chronology">@lang('pages.updates.chronology')</a>
								</span>
							</span>
							<span class="link date">{{$author->updated_at->diffForHumans()}}</span>
							<span class="link samlib"><a href="http://samlib.ru{{$author->link}}{{$group->link}}">{{$author->link}}{{$group->link}}</a></span>
						</div>

						<br /><br />
					</div>

					<div class="pages">
						@include('pages.list', ['pages' => $group->pages()->orderBy('title')->paginate(10), 'exclude' => ['author', 'group']])
					</div>
@stop
