
@if ($breadcrumbs)
			<ul class="breadcrumbs">
@foreach ($breadcrumbs as $breadcrumb)
	@if (!($breadcrumb->first || $breadcrumb->entity))
		<li class="arrow" />
	@endif

	<li @class(['entity' => $breadcrumb->entity, 'active' => $breadcrumb->last])>
		<a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a>
	</li>
@endforeach
				</ul>
@endif
