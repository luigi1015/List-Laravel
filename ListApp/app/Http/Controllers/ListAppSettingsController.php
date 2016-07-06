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
	 * Returns true if the current user is an admin.
	 */
	public static function isCurrentUserAdmin()
	{
		$role = \ListApp\Userrole::where('userroleid', \Auth::user()->userrole )->first();
		return $role->name == 'Admin';
	}

	/**
	 * Returns true if the current user has user role of root.
	 */
	public static function isCurrentUserRoot()
	{
		$role = \ListApp\Userrole::where('userroleid', \Auth::user()->userrole )->first();
		return $role->name == 'Root';
	}
}
