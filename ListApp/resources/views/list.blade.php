@extends('layouts.masterTemplate')

@section('title')
	{{ $list->title or 'Unknown List' }}
@stop

@section('headerContent')
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
@stop

@section('content')
	@if( isset($list) )
		<center><h1>{{ $list->title }}</h1></center>
		<br>
		@if( Auth::check() and Auth::user()->username == $username)
		<table class="ghostTable">
			<tr>
				<td>
					@if( isset($listowner) )
						<table class="menuTable">
							<tr>
								<th><h3>Your Lists</h3></th>
							</tr>
							@foreach( $lists as $weblist )
							<tr>
								<td>
									<a href='/user/{{ $listowner->username }}/list/{{ $weblist->nameid }}'>{{ $weblist->title }}</a>
								</td>
							</tr>
							@endforeach
						</table>
					@endif
				</td>
				<td>
		<form method="post" action="/updateweblist">
			<input type="hidden" name="listId" value="{{ $list->weblistid }}">
			<input type="hidden" name="listNameId" value="{{ $list->nameid }}">
			<input type="hidden" name="username" value="{{ $username }}">
			{!! csrf_field() !!}
		@endif
			<table ng-app="" class="menuTable">
				<tr>
					<th class="selectBox">Select</th>
					<th>Item</th>
					<th class="deleteBox">Delete</th>
				</tr>
			@foreach( $list->listitems as $key=>$listItem )
				<tr ng-class="{selectedRow: checkbox{{$key}}}" @if( $listItem->checked == true ) class='selectedRow' @endif>
					<td class="selectBox">
						<input type="checkbox" id="checkbox-selected-{{ $listItem->listitemid }}" name="checkbox-selected-{{ $listItem->listitemid }}" value="checkbox-{{ $listItem->listitemid }}" ng-model="checkbox{{$key}}" ng-init="checkbox{{$key}}={{$listItem->checked ? 'true' : 'false'}}" @if( $listItem->checked == true) checked @endif>
					</td>
					<td>
						<label for="checkbox-selected-{{ $listItem->listitemid }}">{{ $listItem->description }}</label>
					</td>
					<td class="deleteBox">
						<input type="checkbox" id="checkbox-delete-{{ $listItem->listitemid }}" name="checkbox-delete-{{ $listItem->listitemid }}" value="checkbox-{{ $listItem->listitemid }}">
					</td>
				</tr>
			@endforeach
			</table>
		@if( Auth::check() and Auth::user()->username == $username)
			<br>
			<label for="public">Public</label>
			<input type="checkbox" id="public" name="public" value="public"@if($list->public == true) checked @endif>
			<br>
			<input type="submit" value="Update">
		</form>
		<br>
		<fieldset>
			<legend>Add List Item</legend>
			<form method="post" action="/additem">
				{!! csrf_field() !!}
				<input type="hidden" name="listId" value="{{ $list->weblistid }}">
				<label for="itemDescription">Item Description:</label>
				<input type="text" name="itemDescription">
				<br>
				<input type="submit">
			</form>
		</fieldset>
				</td>
			</tr>
		</table>
		@endif
	@else
		Could not find the list.
	@endif
@stop
