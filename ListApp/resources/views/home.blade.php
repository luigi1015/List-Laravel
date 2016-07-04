@extends('layouts.masterTemplate')

@section('title')
	Home
@stop

@section('content')
		Welcome, {{ Auth::user()->name }}!
		<br>
{{--
		Your user id is {{ Auth::user()->userid }}
		<br>
--}}
		Your Lists:
		<br>
		@foreach( $lists as $list )
			<a href='/list/{{ $list->nameid }}'>{{ $list->title }}</a>
			<br>
		@endforeach

		<fieldset>
			<legend>Add List</legend>
			<form method="post" action="/addweblist">
				{!! csrf_field() !!}
				<label for="listTitle">List Title:</label>
				<input type="text" name="listTitle">
				<br>
				<label for="listId">List ID (must contain only letters, numbers, dashes, and/or underscores):</label>
				<input type="text" name="listId">
				<br>
				<input type="submit">
			</form>
		</fieldset>
@stop
