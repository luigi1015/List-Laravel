@extends('layouts.masterTemplate')

@section('title')
	Users
@stop

@section('content')
		Users:
		<br>
		@foreach( $users as $user )
			{{ $user->userid }} - {{ $user->username }} - {{ $user->name }}
			<br>
		@endforeach
@stop
