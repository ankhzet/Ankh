@extends('layouts.common')

@section('title')@kept(author)<a href="{{ route('authors.show', $page->author) }}">{{$page->author->fio}}</a> - @endkept<a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>@stop
@section('title-plain'){{$page->author->fio}}. {{$page->group->title}}: {{$page->title}}@stop
@section('rss')page/{{$page->id}}@stop

@section('moderation')
@admin()
	@i-menu(admin)
		@m-item('common.edit', route('pages.edit', $page) )
		@m-delete('common.delete', route('pages.destroy', $page) )
	@endmenu
@endadmin
@stop

@section('content')

<div class="cnt-item page">
	<div class="title">
		<span class="link date">{{$page->updated_at->ago()}}</span>
		@samlib($page)
	</div>
	<div class="text quote">{!! $page->annotation !!}</div>
</div>

<div class="cnt-item page">
</div>

<div class="version">
	<div class="text">
@foreach($versions as $month => $perMonth)
		<div class="cnt-item"><span class="title">{{$month}}</span></div>
@foreach($perMonth as $v)
		<div class="cnt-item">
@i-menu(icons)
	@m-item(!file-text-o, route('pages.read', [$page, $v[0]]) )
	@m-item(!download, route('pages.download', [$page, $v[0]]) )
@endmenu
			&nbsp;{{$v[0]->encode('d, H:i')}}
@if ($v[2])
			<span class="text">
				<span class="v-diff">&rarr; @lang('common.read-diff'):
					<ul class="versions">
@foreach($v[2] as $month2 => $perMonth2)
						<li>
							<span class="nowrap">{{$month2}}</span><br />
@foreach($perMonth2 as $v2)
							<a href="{{ route('pages.diff', [$page, $v[0], $v2]) }}" noindex nofollow >{{$v2->encode('d.m')}}</a>
@endforeach
						</li>
@endforeach
					</ul>
				</span>
			</span>
@endif
			<span class="link size right">{{file_size(@$v[1]->change['new'])}}</span>
		</div>
@endforeach
@endforeach
	</div>
</div>


@stop

