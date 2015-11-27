<div></div>
@if (!${"{$name}s"}->isEmpty())
	@foreach (${"{$name}s"} as $item)
				@include("{$name}s.item", [$name => $item, 'exclude' => @$exclude ?: []])
	@endforeach
				{!!${"{$name}s"}->render()!!}
@else
				@lang("pages.{$name}s.no-{$name}s")
@endif
