
@if (!$pages->isEmpty())
	@foreach ($pages as $page)
				@include('pages.item', ['page' => $page, 'exclude' => isset($exclude) ? $exclude : []])
	@endforeach
				{!!$pages->render()!!}
@else
				@lang('pages.pages.no-pages')
@endif
