@extends('layouts.common')

@section('title') {{$group->fio}} @stop

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
