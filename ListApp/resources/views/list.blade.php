@extends('layouts.masterTemplate')

@section('title')
	{{ $list->title }}
@stop

@section('content')
		List title: {{ $list->title }}
		<br>
		List items:
		<br>
		@foreach( $listItems as $listItem )
			{{ $listItem->description }}
			<br>
		@endforeach
@stop
