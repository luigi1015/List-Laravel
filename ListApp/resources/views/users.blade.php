@extends('layouts.masterTemplate')

@section('title')
	Users
@stop

@section('content')
		Users:
		<br>
		@foreach( $users as $user )
			<a href='/settings/user/{{ $user->username }}'>{{ $user->userid }} - {{ $user->username }} - {{ $user->name }}</a>
			<br>
		@endforeach
@stop
