<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ListController extends Controller
{
	/**
	 * Returns a Collection of Weblists of the current logged in user.
	 */
	public static function getUsersWeblists()
	{
		$weblistIds = \DB::table('permission_user_weblist')->where('usersid', \Auth::user()->userid)->pluck('weblist_id');
		return \App\Weblist::whereIn('id', $weblistIds)->get();
	}
	/*
	public static function getWeblistsByUser( $userId )
	{
		$weblistIds = \DB::table('permission_user_weblist')->where('usersid', $userId)->pluck('weblist_id');
		return view('home')->with('lists', \App\Weblist::whereIn('id', $weblistIds)->get());
	}
	*/

	/**
	 * Returns a Weblist of the given id.
	 */
	public static function getWeblistById( $weblistId )
	{
		$selectedWeblist = \App\Weblist::with('listitems', 'listitems.tags')->where('id', $weblistId)->first();
		return $selectedWeblist;
	}

	/**
	 * Adds an item to a weblist.
	 */
	public static function addItemToWeblist( $weblistId, $itemDescription )
	{
		$selectedWeblist = \App\Weblist::where('id', $weblistId)->first();
		$newListItem = new \App\Listitem();
		$newListItem->description = $itemDescription;
		$newListItem->save();
		$selectedWeblist->listitems()->attach($newListItem->id);
	}

	/**
	 * Deletes an item from a weblist.
	 */
	public static function deleteItemFromWeblist( $itemId )
	{
		$listItemToDelete = \App\Listitem::where('id', $itemId)->first();
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
		$tagToDelete = \App\Tag::where('id', $tagId)->first();
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
