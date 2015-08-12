<b></b>
<div style="font-size: 14px; color: #333; background: #e9e9e9; margin: 0; padding: 10px;">
	<span>{{ $update->relatedGroup()->title }} - {{ $update->relatedPage()->title }}</span>
	<span style="font-size: 80%;">
		<b>{{ file_size($update->value) }} ({!! $update->diffString('<span style="color: :color">:delta</span>', [false=>'red', true=>'green']) !!})</b>
		<b style="float: right; width: 20%;"> {{ $feed->created }} </b><br />
		<span style="color: #89a;">
			SAMLIB: <a style="color: #89b;" href="http://samlib.ru{{ $link = $update->relatedPage()->absoluteLink() }}">http://samlib.ru{{ $link }}</a><br />
			Все версии: <a style="color: #89b;" href="{{ $feed->url }}">{{ $feed->url }}</a><br />
			Последняя версия полностью: <a style="color: #89b;" href="{{ $route = route('pages.show', $update->relatedPage()) }}">{{ $route }}</a><br />
		</span>
	</span>
	<div style="font-size: 90%; color: #777">{{ \Illuminate\Support\Str::limit($update->relatedPage()->annotation, 250) }}</div>
</div>
