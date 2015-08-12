<div></div>
@if (!$updates->isEmpty())
	@foreach ($updates->daily() as $origin => $groupped)
<?php
	$origin = $updates->dateOrigin($origin);
	$today = ($delta = $updates->dateDaysDiff($origin)) == 0;
	switch ($delta) {
	case 0: $ago = \Lang::get('pages.updates.today'); break;
	case 1: $ago = \Lang::get('pages.updates.yesterday'); break;
	default: $ago = $origin->ago(); break;
	}
?>
				<small><b>&nbsp; @if($today) @lang('pages.updates.today') @else {{ $ago }} @endif</b></small>
		@foreach ($updates->authorly($groupped) as $aid => $authored)
				<div class="cnt-item" style="overflow: hidden;">
					<div class="title">
						<a href="{{ route('authors.show', $author = $updates->authorOrigin($aid)) }}">{{$author->fio}}</a>
					</div>
			@foreach ($authored as $update)
					@include('updates.item', ['update' => $update, 'last' => $update == last($authored)])
			@endforeach
				</div>
		@endforeach

	@endforeach

				{!!$updates->render()!!}
@else
				@lang('pages.updates.no-updates')
@endif
