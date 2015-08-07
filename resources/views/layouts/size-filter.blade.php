@if(isset($sizes) && count($sizes))
		<div class="content-header filters">
						@lang('common.filter-by-size'):
						<ul class="filter">
@foreach($sizes as $range => $count)
@if(mb_strtolower($range) != mb_strtolower(Request::get('size-range')))
							<li><a href="?size-range={{$range}}">{{$range}}</a> <sup>{!!$count!!}</sup></li>
@else
							<li><span class="selected">{{$range}}</span> <sup>{!!$count!!}</sup></li>
@endif
@endforeach
						</ul>
					</div>
@endif
