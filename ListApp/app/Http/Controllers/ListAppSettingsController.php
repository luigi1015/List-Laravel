<?php

namespace ListApp\Http\Controllers;

use Illuminate\Http\Request;

use ListApp\Http\Requests;

class ListAppSettingsController extends Controller
{
	/**
	 * Returns a collection of all the users if the current logged in user has the right permissions.
	 */
	public static function getUsers()
	{
		if( ListAppSettingsController::isCurrentUserAdmin() || ListAppSettingsController::isCurrentUserRoot() )
		{
			return \ListApp\User::all();
		}
		else
		{
			return [];
		}
	}

	/**
	 * Returns info about a user if the current logged in user has the right permissions.
	 */
	public static function getUser( $username )
	{
		if( ListAppSettingsController::isCurrentUserAdmin() || ListAppSettingsController::isCurrentUserRoot() )
		{
			return \ListApp\User::where( 'username', $username )->first();
		}
		else
		{
			return [];
		}
	}

	/**
	 * Returns true if the current user is an admin.
	 */
	public static function isCurrentUserAdmin()
	{
		if( \Auth::check() )
		{
			$role = \ListApp\Userrole::where('userroleid', \Auth::user()->userrole )->first();
		}
		else
		{
			return false;
		}

		if( is_null($role) )
		{
			return false;
		}
		else
		{
			return $role->name == 'Admin';
		}
	}

	/**
	 * Returns true if the current user has user role of root.
	 */
	public static function isCurrentUserRoot()
	{
		if( \Auth::check() )
		{
			$role = \ListApp\Userrole::where('userroleid', \Auth::user()->userrole )->first();
		}
		else
		{
			return false;
		}

		if( is_null($role) )
		{
			return false;
		}
		else
		{
			return $role->name == 'Root';
		}
	}
}
