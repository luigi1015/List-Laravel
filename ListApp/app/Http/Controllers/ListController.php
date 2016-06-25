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
		//$weblistIds = \DB::table('permission_user_weblist')->where('usersid', \Auth::user()->userid)->pluck('weblistid');
		$weblistIds = \DB::table('permission_user_weblist')->join('permissions', 'permission_user_weblist.permissionid', '=', 'permissions.permissionid')->whereIn('title', ['Edit', 'Read', 'Owner'])->where('usersid', \Auth::user()->userid)->pluck('weblistid');
		//return \ListApp\Weblist::whereIn('id', $weblistIds)->get();
		return \ListApp\Weblist::whereIn('weblistid', $weblistIds)->orderBy('title','asc')->get();
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
	public static function getWeblistByNameid( $weblistId )
	{
		$selectedWeblist = \ListApp\Weblist::with('listitems', 'listitems.tags')->where('nameid', $weblistId)->first();
		return $selectedWeblist;
	}

	/**
	 * Adds an item to a weblist.
	 */
	public static function addItemToWeblist( $weblistId, $itemDescription )
	{
		//$sc = new \Ramsey\Uuid\Codec\StringCodec;

		$uuid = ListAppController::getUUID( 'listitems', 'listitemid' );

		//$uuidString = $sc->encode($uuid);
		$uuidString = vsprintf( '%08s-%04s-%04s-%02s%02s-%012s', $uuid->getFieldsHex() );
		\Log::info( 'UUID: ' . $uuidString );

		$selectedWeblist = \ListApp\Weblist::where('weblistid', $weblistId)->first();

		$newListItem = new \ListApp\Listitem();
		//$newListItem->listitemid = $uuid;
		$newListItem->listitemid = $uuidString;
		$newListItem->description = $itemDescription;
		$newListItem->save();
		//$selectedWeblist->listitems()->attach($newListItem->listitemid);
		//$selectedWeblist->listitems()->attach($uuid);
		$selectedWeblist->listitems()->attach($uuidString);
	}

	/**
	 * Deletes an item from a weblist.
	 */
	public static function deleteItemFromWeblist( $itemId )
	{
		$listItemToDelete = \ListApp\Listitem::where('listitemid', $itemId)->first();
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
		$tagToDelete = \ListApp\Tag::where('tagid', $tagId)->first();
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

	/**
	 * Creates a weblist. Generates a random UUID weblistid for the weblist.
	 * 
	 * Parameters: title, nameid, and userid
	 */
	public static function addWeblist( $newWeblistTitle, $newWeblistNameid, $userid )
	{
		$permission = \ListApp\Permission::where('title','like','Owner')->first();

		$uuid = ListAppController::getUUID( 'weblists', 'weblistid' );
		$newWeblist = new \ListApp\Weblist();
		$newWeblist->weblistid = $uuid;
		$newWeblist->title = $newWeblistTitle;
		$newWeblist->nameid = $newWeblistNameid;
		$newWeblist->save();

		\DB::insert('INSERT INTO permission_user_weblist (permissionid, usersid, weblistid) VALUES (?, ?, ?)', [$permission->permissionid, $userid, $newWeblist->weblistid]);
	}
}
