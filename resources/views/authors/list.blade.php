
@if (!$authors->isEmpty())
	@foreach ($authors as $author)
				@include('authors.item', ['author' => $author, 'exclude' => isset($exclude) ? $exclude : []])
	@endforeach
				{!!$authors->render()!!}
@else
				@lang('pages.authors.no-authors')
@endif
