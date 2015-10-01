@extends('layouts.common')

@section('title') Log viewer @stop
@section('title-plain') Log viewer @stop

@section('moderation')
@admin()
	@i-menu(admin icons)
		@m-item(!download, action('Admin\AdminController@getDownloadLog') )
		@m-delete(!trash, action('Admin\AdminController@deleteDeleteLog') )
	@endmenu
@endadmin
@stop

@section('content')

<div class="log">

<ul>
@foreach($log as $entry)
	<li class="entry">
		<div class="level-{{$entry['level']}}">
			<span class="icon fa fa-exclamation-triangle {{$entry['level']}}"></span>
			<div class="exception">
@if(is_string($entry['message']))
				<div class="message">{{$entry['message']}}</div>
@else

				<div>
					<b class="name">{{$entry['message']['exception']}}</b><br/>
					<span class="detail">{{$entry['message']['file']}} ({{$entry['message']['line']}})</span>
				</div>
				<div class="message">
					{{$entry['message']['message']}}
					{!! HTML::link('subl://open?url=file://' . $entry['message']['file'] . '&line=' . $entry['message']['line'], '', ['class' => 'fa fa-external-link-square'])!!}
				</div>
@endif
@if(@count($entry['stack']) > 1)
		<div class="stack">
			<ul>
@foreach($entry['stack'] as $line)
@if(isset($line['file']))
				<li>
					{{$line['code']}}
					{!! HTML::link('subl://open?url=file://' . $line['file'] . '&line=' . $line['line'], '', ['class' => 'fa fa-external-link-square'])!!}
				</li>
@else
				<li style="color: gray;">{{$line['code']}}</li>
@endif
@endforeach
			</ul>
		</div>
@endif
			</div>
		</div>
	</li>
@endforeach
</ul>

</div>

@stop

