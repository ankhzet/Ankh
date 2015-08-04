@extends('layouts.common')

@section('title')<a href="/authors/{{$author->id}}">{{$author->fio}}</a>@stop
@section('title-plain'){{$author->fio}}@stop
@section('rss')author={{$author->id}}@stop

@section('moderation')
<span class="inline-menu admin">
	<a href="/authors/{{$author->id}}/edit">@lang('common.edit')</a> | <a href="/authors/{{$author->id}}/destroy" data-method="delete">@lang('common.delete')</a>
</span>
@stop

@section('content')

				<div class="author">
					<div class="cnt-item">
						<div class="title">
							<span class="head">

								<span class="pull_right inline-menu">
									  <a href="/authors/{{$author->id}}/pages">@lang('pages.pages.list')</a>
									| <a href="/authors/chronology/24">@lang('pages.updates.chronology')</a>
									| <a href="/authors/check/24">@lang('pages.updates.check')</a>
								</span>
							</span>
							<span class="link date">{{$author->updated_at->ago()}}</span>
							<span class="link samlib"><a href="http://samlib.ru{{$author->link}}">{{$author->link}}</a></span>
						</div>

						<br/><br/>
					</div>
				</div>

				<div class="groups">
					@include('groups.list', ['groups' => $author->groups()->paginate(10)])
				</div>
@stop
