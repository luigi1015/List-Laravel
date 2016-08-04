@extends('layouts.masterTemplate')

@section('title')
	Lists of {{ $username or 'Unknown' }}
@stop

@section('content')
		<h1 class="pageTitle">Lists of {{ $username or 'Unknown' }}</h1>
		<br>
		@if( isset($lists) && !empty($lists) )
			<table>
			@foreach( $lists as $list )
				<tr>
					<td class="tableList">
						<a href='/user/{{ $username or "unknown" }}/list/{{ $list->nameid }}'>{{ $list->title  or 'Unknown'}}</a>
					</td>
				</tr>
			@endforeach
			</table>
		@endif
@stop
