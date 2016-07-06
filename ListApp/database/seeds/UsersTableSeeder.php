<?php

use Illuminate\Database\Seeder;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use ListApp\Http\Controllers\ListAppController;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds for the users table.
	 *
	 * @return void
	 */
	public function run()
	{
		$uuid = ListAppController::getUUID( 'users', 'userid' );

		$role = \ListApp\Userrole::where('name', 'Root')->first();

		DB::table('users')->insert([
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'userid' => $uuid,
			'name' => 'Jeff',
			'username' => 'luigi1015',
			'email' => 'j2013@crone.me',
			'password' => \Hash::make('bowser1015'),
			'active' => 1,
			'userrole' => $role->userroleid
		]);
		/*\Log::info('Created user with UUID: ' . $uuid );*/
    }
}
