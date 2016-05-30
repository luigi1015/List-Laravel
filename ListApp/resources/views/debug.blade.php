@extends('layouts.masterTemplate')

@section('title')
	Debug - {{ $environment }}
@stop

@section('content')
		<h1>Debug - {{ $environment }}</h1>
		
		Debug: {{ $debugging }}
		<br>
		<br>
		Logged in: {{ $loggedin }}
		<br>
		<br>
		User: {{ $user }}
		<br>
		<br>
		UUID: {{ $uuid }}
		<br>
		<br>
		Database Connection Established: {{ $connectionEstablished }}
		<br>
		<br>
		Databases:
		<br>
		<ul>
			@foreach( $databases as $database )
				<li>{{ isset($database->Database) ? $database->Database : '' }}</li>
			@endforeach
		</ul>
@stop
