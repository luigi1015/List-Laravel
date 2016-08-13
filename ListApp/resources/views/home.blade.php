@extends('layouts.masterTemplate')

@section('title')
	Home
@stop

@section('content')
		<center><h1>Welcome, {{ Auth::user()->name }}!</h1></center>
		<br>
{{--
		Your user id is {{ Auth::user()->userid }}
		<br>
--}}
		<br>
		<table class="menuTable">
			<tr>
				<th><h3>Your Lists</h3></th>
			</tr>
		@foreach( $lists as $list )
			<tr>
				<td>
					<a href='/user/{{ Auth::user()->username }}/list/{{ $list->nameid }}'>{{ $list->title }}</a>
				</td>
			</tr>
		@endforeach
		</table>
		<br>
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
