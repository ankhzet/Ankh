
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
@if     ($update->type == \Ankh\Update::U_ADDED)
							<b class="delta green">занесено в БД {!! $update->diffString('(:delta)') !!}</b>
@elseif ($update->type == \Ankh\Update::U_DELETED)
							<b class="delta red">удалено из БД {!! $update->diffString('(:delta)') !!}</b>
@elseif ($update->type == \Ankh\Update::U_RENAMED)
							<b class="delta teal">переименовано</b>
@elseif ($update->type == \Ankh\GroupUpdate::U_INFO)
							<b class="delta olive">информация изменилась</b>
@elseif ($update->type == \Ankh\PageUpdate::U_MOVED)
							<b class="delta blue">перенесено</b>
@elseif ($update->type == \Ankh\PageUpdate::U_DIFF)
							{!! $update->diffString('<b class="delta :color">:delta</b>', ['red', 'green']) !!}
@else
							<b class="delta">{{ $update->changed }}</b>
@endif
							@admin()
								@i-menu(right)
									@m-item('common.delete', route('updates.destroy', $update) )
								@endmenu
							@endadmin
							<span class="link time">{{$update->created_at}}</span>
						</div>
					</div>
