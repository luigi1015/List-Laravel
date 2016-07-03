<?php

use Illuminate\Database\Seeder;

use ListApp\Http\Controllers\ListAppController;

class UserroleTableSeeder extends Seeder
{
	/**
	 * Run the database seeds for the users table.
	 *
	 * @return void
	 */
	public function run()
	{
		//Owner
		$uuid = ListAppController::getUUID( 'userroles', 'userroleid' );

		$role = new \ListApp\Userrole();
		$role->userroleid = $uuid;
		$role->name = "Owner";
		$role->canEdit = true;
		$role->canRead = true;
		$role->canCreate = true;
		$role->canDelete = true;
		$role->save();
		/*\Log::info('Created user role with UUID: ' . $uuid );*/

		//Admin
		$uuid = ListAppController::getUUID( 'userroles', 'userroleid' );

		$role = new \ListApp\Userrole();
		$role->userroleid = $uuid;
		$role->name = "Admin";
		$role->canEdit = true;
		$role->canRead = true;
		$role->canCreate = true;
		$role->canDelete = true;
		$role->save();
		/*\Log::info('Created user role with UUID: ' . $uuid );*/

		//Regular User
		$uuid = ListAppController::getUUID( 'userroles', 'userroleid' );

		$role = new \ListApp\Userrole();
		$role->userroleid = $uuid;
		$role->name = "Regular";
		$role->canEdit = false;
		$role->canRead = false;
		$role->canCreate = false;
		$role->canDelete = false;
		$role->save();
		/*\Log::info('Created user role with UUID: ' . $uuid );*/
    }
}
