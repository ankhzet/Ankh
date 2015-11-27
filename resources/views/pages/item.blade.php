

					<div class="cnt-item @trashed($page) ">
						<div class="title">
							<span class="head">
@kept(author)
								<a href="{{ route('authors.show', $page->author) }}">{{$page->author->fio}}</a>&nbsp;-
@endkept
								<a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>
								@admin()
									@i-menu(admin icons normal-icons)
										@m-item(!edit, route('pages.edit', $page) )
										@m-delete(!trash, route('pages.destroy', $page) )
									@endmenu
								@endadmin
							</span>
							@samlib($page)
						</div>
						<div class="text">{!! cleanup_annotation($page->annotation) !!}</div>
						<ul class="text">
							<li><a href="{{ route('pages.show', $page) }}">@lang('pages.pages.all-versions')</a></li>
							<li><span class="size">{{file_size($page->size, 1)}}</span></li>
@kept(group)
							<li>@lang('pages.groups.group'):
								<a href="{{ route('groups.show', $page->group) }}">{{$page->group->title}}</a>
							</li>
@endkept
						</ul>
					</div>

