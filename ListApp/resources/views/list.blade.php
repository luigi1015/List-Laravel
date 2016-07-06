@extends('layouts.masterTemplate')

@section('title')
	{{ $list->title or 'Unknown List' }}
@stop

@section('content')
	@if( isset($list) )
		List title: {{ $list->title }}
		<br>
		<form method="post" action="/updateweblist">
		<input type="hidden" name="listId" value="{{ $list->weblistid }}">
		<input type="hidden" name="listNameId" value="{{ $list->nameid }}">
		{!! csrf_field() !!}
		<input type="checkbox" id="public" name="public" value="public">
		<label for="public">Public</label>
		<br>
		List items:
		<br>
		<table>
			<tr>
				<th>Select</th>
				<th>Item</th>
				<th>Delete</th>
			</tr>
		@foreach( $list->listitems as $listItem )
			<tr @if( $listItem->checked == true) class='selectedRow' @endif>
				<td>
					<input type="checkbox" id="checkbox-selected-{{ $listItem->listitemid }}" name="checkbox-selected-{{ $listItem->listitemid }}" value="checkbox-{{ $listItem->listitemid }}" @if( $listItem->checked == true) checked @endif>
				</td>
				<td>
					<label for="checkbox-selected-{{ $listItem->listitemid }}">{{ $listItem->description }}</label>
				</td>
				<td>
					<input type="checkbox" id="checkbox-delete-{{ $listItem->listitemid }}" name="checkbox-delete-{{ $listItem->listitemid }}" value="checkbox-{{ $listItem->listitemid }}">
				</td>
			</tr>
		@endforeach
		</table>
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
	@else
		Could not find the list.
	@endif
@stop
