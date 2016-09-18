@extends('layouts.masterTemplate')

@section('title')
	User
@stop

@section('content')
	@if( isset($user) )
		{{ $user->userid }} - {{ $user->username }} - {{ $user->name }}
		<br>
		API Key: placeholder here
	@else
		That user was not found.
	@endif
	<br>
	<a href='/settings/users'>Back to users</a>
@stop
