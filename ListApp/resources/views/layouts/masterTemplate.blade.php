<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>@yield('title','Welcome to Lists!')</title>
		<link rel="stylesheet" href="{{ URL::asset('css/masterTemplate.css') }}">
		@yield( 'headerContent' )
	</head>
	<body>
		<a id="top"></a>
		<header>
			<nav>
				<a href='/' @if( isset($activePage) && $activePage == 'root' ) class='active' @endif>Homepage</a><!--
				-->@if( Auth::check() )<!--
					-->{{-- You are logged in as {{ Auth::user()->name }} --}}<!--
					--><a href='/home' @if( isset($activePage) && $activePage == 'userhome' ) class='active' @endif>User Home</a><!--
					--><a href='/settings' @if( isset($activePage) && $activePage == 'settings' ) class='active' @endif>Settings</a><!--
					--><a href='/logout'>Logout</a><!--
				-->@else<!--
					--><a href='/login' @if( isset($activePage) && $activePage == 'login' ) class='active' @endif>Login</a><!--
					--><a href='/register' @if( isset($activePage) && $activePage == 'register' ) class='active' @endif>Register</a>
				@endif
			</nav>
		</header>
		@if( Session::has('message') )
			<p class="flash-message">{{ Session::get('message') }}</p>
		@endif
		@if( Session::has('error') )
			<p class="flash-error">{{ Session::get('error') }}</p>
		@endif
		@if( count($errors) > 0 )
			@foreach( $errors->all() as $error )
				<p class="flash-error">{{ $error }}</p>
			@endforeach
		@endif
		<main>
			@yield( 'content' )
		</main>
	</body>
</html>
