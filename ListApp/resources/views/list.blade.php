@extends('layouts.masterTemplate')

@section('title')
	{{ $list->title }}
@stop

@section('content')
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
		@foreach( $list->listitems as $listItem )
			<input type="checkbox" id="checkbox-{{ $listItem->listitemid }}" name="checkbox-{{ $listItem->listitemid }}" value="checkbox-{{ $listItem->listitemid }}">
			<label for="checkbox-{{ $listItem->listitemid }}">{{ $listItem->description }}</label>
			{{--
			@foreach( $listItem->tags as $tag )
				<form method="post" action="/deletetag">
					{!! csrf_field() !!}
					<input type="hidden" name="listId" value="{{ $list->weblistid }}">
					<input type="hidden" name="itemId" value="{{ $listItem->listitemid }}">
					<input type="hidden" name="tagId" value="{{ $tag->tagid }}">
					{{ $tag->description }}
					<br>
					<input type="submit" value="Delete Tag">
				</form>
			@endforeach
			<form method="post" action="/deleteitem">
				{!! csrf_field() !!}
				<input type="hidden" name="listId" value="{{ $list->weblistid }}">
				<input type="hidden" name="itemId" value="{{ $listItem->listitemid }}">
				<br>
				<input type="submit" value="Delete Item">
			</form>
			--}}
			<br>
		@endforeach
		<input type="submit" value="Update">
		</form>

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
@stop
