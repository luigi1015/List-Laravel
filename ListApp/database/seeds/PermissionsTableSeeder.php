<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the permissions table.
	 *
	 * @return void
	 */
	public function run()
	{
		$permission = new \App\Permission();
		$permission->permissionid = 1;
		$permission->title = 'Owner';
		$permission->save();

		$permission = new \App\Permission();
		$permission->permissionid = 2;
		$permission->title = 'Edit';
		$permission->save();

		$permission = new \App\Permission();
		$permission->permissionid = 3;
		$permission->title = 'Read';
		$permission->save();
	}
}
