<?php

namespace ListApp\Http\Controllers;

use Illuminate\Http\Request;

use ListApp\Http\Requests;

use Crypt;

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
	public static function getWeblistByUseridAndNameid( $userid, $weblistNameid )
	{
		$info = \DB::table('weblists')->join('permission_user_weblists','weblists.weblistid','=','permission_user_weblists.weblistid')->join('permissions','permissions.permissionid','=','permission_user_weblists.permissionid')->where('permissions.title','Owner')->where('weblists.nameid',$weblistNameid)->where('permission_user_weblists.usersid',$userid)->pluck('weblists.weblistid');

		//\Log::info($info);
		if( !empty($info) )
		{
			if( ListController::canReadWeblist($info[0]) )
			{
				$selectedWeblist = \ListApp\Weblist::with('listitems', 'listitems.tags')->where('weblistid', $info[0])->first();
				return $selectedWeblist;
			}
			else
			{
				\Log::error( 'getWeblistByUseridAndNameid( $userid, $weblistId ): User "' . \Auth::user()->userid . '" tried to access weblist "' . $weblistNameid . '" without permission.' );
			}
		}
	}

	/**
	 * Adds an item to a weblist.
	 */
	public static function addItemToWeblist( $weblistId, $itemDescription )
	{
		$uuid = ListAppController::getUUID( 'listitems', 'listitemid' );

		\Log::info( 'Encrpyed description: ' . Crypt::encrypt($itemDescription) );

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

	/**
	 * Returns wether the current logged in user (or anybody if nobody is logged in) can read a Weblist of the given id.
	 */
	public static function canReadWeblist( $weblistId )
	{
		if( \ListApp\Weblist::where('weblistid', $weblistId)->first()->public == true )
		{
			return true;
		}
		else if( \Auth::check() )
		{//If someone is logged in.
			$info = \DB::table('weblists')->join('permission_user_weblists','weblists.weblistid','=','permission_user_weblists.weblistid')->join('permissions','permissions.permissionid','=','permission_user_weblists.permissionid')->where('weblists.weblistid',$weblistId)->where('permission_user_weblists.usersid',\Auth::user()->userid)->first();

			if( is_null($info) )
			{
				return false;
			}
			else
			{
				return $info->canRead == true;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Returns the owner user id of the Weblist of the given id.
	 */
	public static function getWeblistOwner( $weblistId )
	{
		$userid = \DB::table('permission_user_weblists')->join('permissions', 'permission_user_weblists.permissionid', '=', 'permissions.permissionid')->where('permissions.title', 'Owner')->where('weblistid', $weblistId)->pluck('usersid');

		if( is_null($userid) )
		{
			return null;
		}
		else
		{
			return $userid[0];
		}
	}

	/**
	 * Returns the lists owned by user indicated by $username and viewable bt the logged in user (or public lists if user isn't logged in).
	 */
	public static function getWeblistsOfUser( $username )
	{
		$user = \ListApp\User::where('username', $username)->first();
		$weblistIds = \DB::table('permission_user_weblists')->join('permissions', 'permission_user_weblists.permissionid', '=', 'permissions.permissionid')->where('permissions.title', 'Owner')->where('usersid', $user->userid)->pluck('weblistid');

		//$weblists = \ListApp\Weblist::where('public', true)->get();
		$weblists = collect([]);
		foreach( $weblistIds as $weblistId )
		{
			$weblist = \ListApp\Weblist::where('weblistid', $weblistId)->first();
			if( $weblist->public == true )
			{
				$weblists->push($weblist);
			}
		}
		return $weblists;
	}
}
