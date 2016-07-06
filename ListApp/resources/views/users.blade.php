@extends('layouts.masterTemplate')

@section('title')
	Users
@stop

@section('content')
		Users:
		<br>
		@foreach( $users as $user )
			{{ $user->name }}
			<br>
		@endforeach
@stop
