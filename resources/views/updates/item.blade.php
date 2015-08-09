
					<div class="title update dotted">

@if ($group = $update->relatedGroup())
						<span class="head">
							<a href="{{ route('groups.show', $group) }}">{{$group->title}}</a>
						</span>
@endif

@if ($page = $update->relatedPage())
						<span class="head">
							: <a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>
						</span>
@endif

						<div class="head small">
@if     ($update->type == \Ankh\Update::U_ADDED)
							<span class="delta green"><b>занесено в БД @if($update->delta) ({{diff_size($update->delta)}})@endif</b></span>
@elseif ($update->type == \Ankh\Update::U_DELETED)
							<span class="delta red"><b>удалено из БД @if($update->delta) ({{diff_size($update->delta)}})@endif</b></span>
@elseif ($update->type == \Ankh\Update::U_RENAMED)
							<span class="delta teal"><b>переименовано</b></span>
@elseif ($update->type == \Ankh\GroupUpdate::U_INFO)
							<span class="delta olive"><b>информация изменилась</b></span>
@elseif ($update->type == \Ankh\PageUpdate::U_MOVED)
							<span class="delta blue"><b>перенесено</b></span>
@elseif ($update->type == \Ankh\PageUpdate::U_DIFF)
							@if($update->delta != 0)<span class="delta green"><b>{{diff_size($update->delta)}}</b></span>@endif
@else
							<span class="delta"><b>{{ $update->changed }}</b></span>
@endif
							<span class="link time">{{$update->created_at}}</span>
						</div>
					</div>
