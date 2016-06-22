<?php

use Illuminate\Database\Seeder;
use ListApp\Http\Controllers\ListAppController;

class PermissionsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the permissions table.
	 *
	 * @return void
	 */
	public function run()
	{
		$uuid = ListAppController::getUUID( 'permissions', 'permissionid' );
		$permission = new \ListApp\Permission();
		$permission->permissionid = $uuid;
		$permission->title = 'Owner';
		$permission->save();

		$uuid = ListAppController::getUUID( 'permissions', 'permissionid' );
		$permission = new \ListApp\Permission();
		$permission->permissionid = $uuid;
		$permission->title = 'Edit';
		$permission->save();

		$uuid = ListAppController::getUUID( 'permissions', 'permissionid' );
		$permission = new \ListApp\Permission();
		$permission->permissionid = $uuid;
		$permission->title = 'Read';
		$permission->save();
	}
}
