
@if (!$groups->isEmpty())
	@foreach ($groups as $group)
				@include('groups.item', ['group' => $group])
	@endforeach
				{!!$groups->render()!!}
@else
				@lang('pages.groups.no-groups')
@endif
