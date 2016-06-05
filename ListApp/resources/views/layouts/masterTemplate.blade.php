<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>@yield('title','Welcome to Lists!')</title>
		<link rel="stylesheet" href="{{ URL::asset('css/masterTemplate.css') }}">
	</head>
	<body>
		@if( Session::has('message') )
			<p class="flash-message">{{ Session::get('message') }}</p>
		@endif
		@if( Session::has('error') )
			<p class="flash-error">{{ Session::get('error') }}</p>
		@endif
		<a href='/'>Homepage</a>
		@if( Auth::check() )
			You are logged in as {{ Auth::user()->name }}
			<a href='/home'>User Home</a>
			<a href='/logout'>Logout</a>
		@else
			<a href='/login'>Login</a>
			<a href='/register'>Register</a>
		@endif
		<br>
		@yield( 'content' )
	</body>
</html>
