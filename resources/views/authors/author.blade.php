@extends('layouts.common')

@section('content')
	<?php View::share('page_title', $author->fio); ?>
	<div class="author">
		{{$author->fio}} <span class="right">{{$author->link}}</span>
	</div>
@stop
