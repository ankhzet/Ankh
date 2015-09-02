
					<div class="title update @if(!$last) dotted @endif ">
@if ($group = $update->relatedGroup())
						<span class="head">
							<a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>@if ($update->relatedPage()):@endif
						</span>

@endif
@if ($page = $update->relatedPage())
						<span class="head"><a href="{{ route('pages.show', $page) }}">{{$page->title}}</a></span>

@endif
						<div class="head small">
							{!! $update_tag !!}
							@admin()
								@i-menu(right)
									@m-delete('common.delete', route('updates.destroy', $update) )
								@endmenu
							@endadmin
							<span class="link time">{{$update->created_at}}</span>
						</div>
					</div>
