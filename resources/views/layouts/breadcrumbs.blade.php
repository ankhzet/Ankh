@if ($breadcrumbs)
<ul class="breadcrumbs">
@foreach ($breadcrumbs as $breadcrumb)
	@if (!$breadcrumb->last)
	<li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
	<li class="arrow"> &rarr; </li>
	@else
	<li class="active"><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
	@endif
@endforeach
</ul>
@endif
