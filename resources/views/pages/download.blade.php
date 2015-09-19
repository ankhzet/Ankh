@extends('layouts.common')

@section('title')@kept(author)<a href="{{ route('authors.show', $page->author) }}">{{$page->author->fio}}</a> - @endkept<a href="{{ route('pages.show', $page) }}">{{$page->title}}</a>@stop
@section('title-plain'){{$page->author->fio}}. {{$page->group->title}}: {{$page->title}}@stop

@section('content')

<?php
	$data = $version->downloadable();
	$transform = new \Ankh\Downloadable\Transforms();
?>

<div class="cnt-item page">
	<div class="title">
		<span class="link date">{{with($page->created_at ?: \Date::now())->ago()}}</span>
	</div>
	<div class="text quote">{!! strip_unwanted_tags($page->annotation, ['font']) !!}</div>
</div>

<div class="download-page">
	<b>@lang('Page version'):</b>
	<ul>
		<li>{{$version->encode('j F, Y H:i')}}</li>
	</ul>

	<br/>
	<b>@lang('Encoding'):</b>
	<br /><br />
	<form>
		<div class="edit">
	<table>
		<thead class="header">
			<tr>
				<td></td>
				<td><div>Not zipped</div></td>
				<td><div>Zipped</div></td>
			</tr>
		</thead>
		<tbody>
@foreach (['html', 'txt'] as $idt => $type)
@foreach (['win1251', 'utf-8'] as $ide => $encoding)
			<tr @if ($ide && !$idt) class="between" @endif>
@if (!$ide)
				<td rowspan=2>{{strtoupper($type)}}</td>
@endif
@foreach ([false => null, true => 'zip'] as $zipped => $zip)
				<td class="download">
					<a href="{{ route('pages.download', array_filter([$page, $version, $zip, $encoding, $type])) }}">{{$encoding}}</a>
					<span class="size">{{ file_size($transform->probeSize($data, [$zip, $encoding, $type])) }}</span>
				</td>
@endforeach
			</tr>
@endforeach
@endforeach
		</tbody>
	</table>
</div>
	</form>

</div>
@stop
