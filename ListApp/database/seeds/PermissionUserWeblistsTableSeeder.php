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
		$user = \App\User::where('name','like','Jeff')->first();

		$weblists = \App\Weblist::all();

		$permission = \App\Permission::where('title','like','Owner')->first();

		foreach( $weblists as $weblist )
		{
			//$user->permissions()->save( $weblist, $permission );
			//$permission->weblist = $weblist;
			//$permission->user = $user;
			//$permission = \App\Permission::where('title','like','Owner')->first();
			//$permission->weblists()->save( $weblist, array() );
			//$permission->users()->save( $user );
			//$user->permissions()->save( $permission, array('weblist_id' => $weblist->id) );
			DB::insert('INSERT INTO permission_user_weblist (permission, usersid, weblist_id) VALUES (?, ?, ?)', [$permission->id, $user->userid, $weblist->id]);
		}

		//$weblists = DB::select( 'SELECT * FROM permission_user_weblist WHERE usersid = ? AND permission = ?', [$user->userid, $permission->id] );
		//$weblists = DB::select( 'SELECT id FROM permission_user_weblist WHERE usersid = ? AND permission = ?', [$user->userid, $permission->id] );
		//\Log::info($weblists);
	}
}
