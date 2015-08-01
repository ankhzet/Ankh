
				<div class="cnt-item group">
					<div class="title">
						<span class="head">
							<a href="/groups/{{$group->id}}">{{$group->title}}</a>
						</span>
						<span class="link samlib"><a href="http://samlib.ru{{$group->author->link}}{{$group->link}}">{{$group->link}}</a></span>
					</div>
					<div class="text">
						{{$group->annotation}}

						@if (($c = with($pages = $group->pages()->paginate($pagesShown = 4))->total()) > 0)
							@foreach ($pages as $page)
								<br />&rarr; <a href="/pages/{{$page->id}}">{{$page->title}}</a>
							@endforeach
							@if (($delta = ($c - $pagesShown)) > 0)
								<br />&rarr; <a href="/groups/{{$group->id}}/pages">+ еще {!!$delta!!}...</a>
							@endif
						@endif
					</div>
				</div>
