@extends('layouts.common')

@section('title') {{$author->fio}} @stop

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
							<span class="link date">{{$author->updated_at->diffForHumans()}}</span>
							<span class="link samlib"><a href="http://samlib.ru{{$author->link}}">{{$author->link}}</a></span>
						</div>

						<br/><br/>
					</div>
				</div>

				<div class="groups">
					@include('groups.list', ['groups' => $author->groups()->paginate(10)])
				</div>
@stop
