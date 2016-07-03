<?php

use Illuminate\Database\Seeder;

class PermissionUserWeblistsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the PermissionUserWeblists table.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = \ListApp\User::where('name','like','Jeff')->first();

		$weblists = \ListApp\Weblist::all();

		$permission = \ListApp\Permission::where('title','like','Owner')->first();

		foreach( $weblists as $weblist )
		{
			//DB::insert('INSERT INTO permission_user_weblist (permission, usersid, weblist_id) VALUES (?, ?, ?)', [$permission->id, $user->userid, $weblist->weblistid]);
			DB::insert('INSERT INTO permission_user_weblists (permissionid, usersid, weblistid) VALUES (?, ?, ?)', [$permission->permissionid, $user->userid, $weblist->weblistid]);
		}

		//\Log::info($weblists);
	}
}
