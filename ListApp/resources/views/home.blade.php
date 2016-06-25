@extends('layouts.masterTemplate')

@section('title')
	Home
@stop

@section('content')
		Welcome to your user home page.
		<br>
		Your user id is {{ Auth::user()->userid }}
		<br>
		Your Lists:
		<br>
		@foreach( $lists as $list )
			<a href='/list/{{ $list->nameid }}'>{{ $list->title }}</a>
			<br>
		@endforeach
@stop
