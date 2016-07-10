@extends('layouts.masterTemplate')

@section('title')
	Lists of {{ $username or 'Unknown' }}
@stop

@section('content')
		Lists of {{ $username or 'Unknown' }}:
		<br>
		@if( isset($lists) && !empty($lists) )
			@foreach( $lists as $list )
				<a href='/user/{{ $username or "unknown" }}/list/{{ $list->nameid }}'>{{ $list->title  or 'Unknown'}}</a>
				<br>
			@endforeach
		@endif
@stop
