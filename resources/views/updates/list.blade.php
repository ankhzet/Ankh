<div></div>
@if (!$updates->isEmpty())
	@foreach ($updates->collect() as $date => $daily)
				<small><b>&nbsp; {{ $updates->ago($date) }}</b></small>

		@foreach ($daily as $slice => $sliced)
			@foreach ($sliced as $author_id => $authorUpdates)

				<div class="cnt-item" style="overflow: hidden;">
					<div class="title">
@if($author = $updates->author($author_id))
						<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a>
@else
						Unknown author @if($author_id) (ID: {{ $author_id }})@endif
@endif
					</div>
				@foreach ($authorUpdates as $update)
					@include('updates.item', ['update' => $update, 'last' => $update == last($authorUpdates)])
				@endforeach
				</div>

			@endforeach
		@endforeach

	@endforeach

				{!!$updates->render()!!}
@else
				@lang('pages.updates.no-updates')
@endif
