				<div class="cnt-item group">
					<div class="title">
						<span class="head">
							@kept(author)<a href="{{ route('authors.show', $group->author) }}">{{$group->author->fio}}</a> - @endkept <a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
						</span>
						@samlib($group)
					</div>
					<div class="text">
						@if (trim($group->annotation) != '') {!! $group->annotation !!} <br/> @endif
@foreach ($group->peekPages($delta, 4)->get() as $page)
								&rarr; <a href="{{ route('pages.show', $page) }}">{{$page->title}}</a><br />
@endforeach
@if ($delta > 0)
								&rarr; <a href="{{ route('groups.pages.index', $group) }}">@lang('pages.pages.another', ['count' => $delta])</a><br />
@endif
					</div>
				</div>
