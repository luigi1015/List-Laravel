@extends('layouts.masterTemplate')

@section('title')
	Login
@stop

@section('content')
		<form method='POST' action='/login'>

		{!! csrf_field() !!}

		<div>
			<label for='username'>Username</label>
			<input type='text' name='username' id='username' value="{{ old('username') }}">
		</div>
		<div>
			<label for='password'>Password</label>
			<input type='password' name='password' id='password'>
		</div>

		<div class='form-group'>
			<input type='checkbox' name='remember' id='remember'>
			<label for='remember' class='checkboxLabel'>Remember me</label>
		</div>

		<button type='submit' class='btn btn-primary'>Login</button>

	</form>
@stop
