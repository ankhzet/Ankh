
					<div class="cnt-item">
						<div class="title">
							<span class="head">
								@if(array_search('author', $exclude) === false)<a href="/authors/{{$page->author->id}}">{{$page->author->fio}}</a> -@endif <a href="/pages/{{$page->id}}">{{$page->title}}</a>
								<span class="inline-menu admin right"> <a href="/pages/{{$page->id}}/delete">удалить</a> </span>
							</span>
							<span class="link samlib"><a href="http://samlib.ru{{$page->author->link}}/{{$page->link}}">{{$page->author->link}}/{{$page->link}}</a></span>
						</div>
						<div class="text">{{$page->annotation}}</div>
						<div class="text">
							<a href="/pages/{{$page->id}}/version">все версии</a> | <span class="size">{{file_size($page->size * 1024, 1)}}</span> @if(array_search('group', $exclude) === false)| Группа: <a href="/groups/{{$page->group->id}}">{{$page->group->title}}</a>@endif
						</div>
					</div>
