@if (!$groups->isEmpty())
	@foreach ($groups as $group)
		@include('groups.item', ['group' => $group])
	@endforeach

	{!!$groups->render()!!}
@else
	No groups oO
@endif
