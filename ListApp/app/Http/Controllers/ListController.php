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
		//$weblistIds = \DB::table('permission_user_weblist')->join('permissions', 'permission_user_weblist.permissionid', '=', 'permissions.permissionid')->whereIn('title', ['Edit', 'Read', 'Owner'])->where('usersid', \Auth::user()->userid)->pluck('weblistid');
		$weblistIds = \DB::table('permission_user_weblists')->join('permissions', 'permission_user_weblists.permissionid', '=', 'permissions.permissionid')->where('canRead', true)->where('usersid', \Auth::user()->userid)->pluck('weblistid');
		return \ListApp\Weblist::whereIn('weblistid', $weblistIds)->orderBy('title','asc')->get();
	}

	/**
	 * Returns a Weblist of the given id.
	 * Throws an exception if the user doesn't have read access to that weblist.
	 */
	public static function getWeblistByNameid( $weblistId )
	{
		$weblist = \ListApp\Weblist::where('nameid', $weblistId)->first();
		$permission = \ListApp\PermissionUserWeblist::where('usersid', \Auth::user()->userid)->where('weblistid', $weblist->weblistid)->first();
		if( \ListApp\Permission::where('permissionid', $permission->permissionid)->first()->canRead == true )
		{
			$selectedWeblist = \ListApp\Weblist::with('listitems', 'listitems.tags')->where('nameid', $weblistId)->first();
			return $selectedWeblist;
		}
		else
		{
			\Log::error( 'getWeblistByNameid( $weblistId ): User "' . \Auth::user()->userid . '" tried to access weblist "' . $weblistId . '" with the permission "' . $permissionId . '" which does not include reading access.' );
			throw new Exception('Access Denied');
		}
	}

	/**
	 * Adds an item to a weblist.
	 */
	public static function addItemToWeblist( $weblistId, $itemDescription )
	{
		$uuid = ListAppController::getUUID( 'listitems', 'listitemid' );

		$uuidString = vsprintf( '%08s-%04s-%04s-%02s%02s-%012s', $uuid->getFieldsHex() );
		//\Log::info( 'UUID: ' . $uuidString );

		$selectedWeblist = \ListApp\Weblist::where('weblistid', $weblistId)->first();

		$newListItem = new \ListApp\Listitem();
		$newListItem->listitemid = $uuidString;
		$newListItem->description = $itemDescription;
		$newListItem->save();
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

		\DB::insert('INSERT INTO permission_user_weblists (permissionid, usersid, weblistid) VALUES (?, ?, ?)', [$permission->permissionid, $userid, $newWeblist->weblistid]);
	}
}
