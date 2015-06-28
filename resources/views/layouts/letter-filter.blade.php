@if(isset($letters) && count($letters))
		<div class="content-header filters">
			filter by name:
			<ul class="filter">
@foreach($letters as $letter => $count)
@if(mb_strtolower($letter) != mb_strtolower(Request::get('letter')))
				<li><a href="?letter={{$letter}}">{{$letter}}</a> <sup>{!!$count!!}</sup></li>
@else
				<li><span class="selected">{{$letter}}</span> <sup>{!!$count!!}</sup></li>
@endif
@endforeach
			</ul>
		</div>
@endif
