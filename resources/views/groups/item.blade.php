				<div class="cnt-item group">
					<div class="title">
						<span class="head">
							<a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
						</span>
						@samlib($group)
					</div>
					<div class="text">
						{{$group->annotation}}

@foreach ($group->peekPages(4, $delta) as $page)
								<br />&rarr; <a href="{{ route('pages.versions', $page) }}">{{$page->title}}</a>
@endforeach
@if ($delta > 0)
								<br />&rarr; <a href="{{ route('groups.pages.index', $group) }}">@lang('pages.pages.another', ['count' => $delta])</a>
@endif
					</div>
				</div>
