@if (!$pages->isEmpty())
	@foreach ($pages as $page)
		@include('pages.item', ['page' => $page, 'exclude' => isset($exclude) ? $exclude : []])
	@endforeach

	{!!$pages->render()!!}
@else
	No pages oO
@endif
