<?php

namespace ListApp\Http\Controllers;

use Illuminate\Http\Request;

use ListApp\Http\Requests;

class ListController extends Controller
{
	/**
	 * Returns a Collection of Weblists of the current logged in user.
	 */
	public static function getUsersWeblists()
	{
		$weblistIds = \DB::table('permission_user_weblist')->where('usersid', \Auth::user()->userid)->pluck('weblist_id');
		//return \ListApp\Weblist::whereIn('id', $weblistIds)->get();
		return \ListApp\Weblist::whereIn('weblistid', $weblistIds)->get();
	}
	/*
	public static function getWeblistsByUser( $userId )
	{
		$weblistIds = \DB::table('permission_user_weblist')->where('usersid', $userId)->pluck('weblist_id');
		return view('home')->with('lists', \ListApp\Weblist::whereIn('id', $weblistIds)->get());
	}
	*/

	/**
	 * Returns a Weblist of the given id.
	 */
	public static function getWeblistById( $weblistId )
	{
		$selectedWeblist = \ListApp\Weblist::with('listitems', 'listitems.tags')->where('id', $weblistId)->first();
		return $selectedWeblist;
	}

	/**
	 * Adds an item to a weblist.
	 */
	public static function addItemToWeblist( $weblistId, $itemDescription )
	{
		$selectedWeblist = \ListApp\Weblist::where('id', $weblistId)->first();
		$newListItem = new \ListApp\Listitem();
		$newListItem->description = $itemDescription;
		$newListItem->save();
		$selectedWeblist->listitems()->attach($newListItem->id);
	}

	/**
	 * Deletes an item from a weblist.
	 */
	public static function deleteItemFromWeblist( $itemId )
	{
		$listItemToDelete = \ListApp\Listitem::where('id', $itemId)->first();
		if( $listItemToDelete )
		{
			$listItemToDelete->delete();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Deletes a tag from an item.
	 */
	public static function deleteTagFromItem( $tagId )
	{
		$tagToDelete = \ListApp\Tag::where('id', $tagId)->first();
		if( $tagToDelete )
		{
			$tagToDelete->delete();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
