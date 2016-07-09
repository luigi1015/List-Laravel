@extends('layouts.masterTemplate')

@section('title')
	Lists of {{ $username or 'Unknown' }}
@stop

@section('content')
		Lists of {{ $username or 'Unknown' }}:
		<br>
		@if( isset($lists) && !empty($lists) )
			@foreach( $lists as $list )
				{{ $list->title or 'Unknown' }}
				<br>
			@endforeach
		@endif
@stop
