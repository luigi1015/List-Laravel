@extends('layouts.masterTemplate')

@section('title')
	{{ $list->title }}
@stop

@section('content')
		List title: {{ $list->title }}
		<br>
		List items:
		<br>
		@foreach( $list->listitems as $listItem )
			<input type="checkbox" name="checkbox-{{ $listItem->id }}" value="checkbox-{{ $listItem->id }}">{{ $listItem->description }}
			@foreach( $listItem->tags as $tag )
				<form method="post" action="/deletetag">
					{!! csrf_field() !!}
					<input type="hidden" name="listId" value="{{ $list->id }}">
					<input type="hidden" name="itemId" value="{{ $listItem->id }}">
					<input type="hidden" name="tagId" value="{{ $tag->id }}">
					{{ $tag->description }}
					<br>
					<input type="submit" value="Delete Tag">
				</form>
			@endforeach
			<form method="post" action="/deleteitem">
				{!! csrf_field() !!}
				<input type="hidden" name="listId" value="{{ $list->id }}">
				<input type="hidden" name="itemId" value="{{ $listItem->id }}">
				<br>
				<input type="submit" value="Delete Item">
			</form>
			<br>
		@endforeach

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
