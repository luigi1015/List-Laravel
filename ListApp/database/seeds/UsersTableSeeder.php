<?php

use Illuminate\Database\Seeder;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Http\Controllers\ListAppController;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds for the users table.
	 *
	 * @return void
	 */
	public function run()
	{
		/*
		$iterations = 1;
		do
		{
			//If have gone through this loop more than a thousand times, something's wrong, give an error and abort.
			if( $iterations >= 1000 )
			{
				\Log::error('There was a problem generating the UUID, been through the UUID generation block ' . $iterations . ' times.' );
				abort(500);
			}

			try
			{
					$uuid = Uuid::uuid4();
			}
			catch( UnsatisfiedDependencyException $e )
			{
				\Log::error('There was a problem generating the UUID: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
				abort(500);
			}
			$usersWithSameID = DB::table('users')->where('userid', $uuid);
		}
		while( $usersWithSameID->count() > 0 );//If a user with the same id is found, create another one.
		//while( !is_null($usersWithSameID) );//If a user with the same id is found, create another one.
		*/
		$uuid = ListAppController::getUUID();

		DB::table('users')->insert([
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'userid' => $uuid,
			/*'id' => 1,*/
			'name' => 'Jeff',
			'username' => 'luigi1015',
			'email' => 'j2013@crone.me',
			'password' => \Hash::make('bowser1015'),
			'active' => 1,
			'type' => 1
		]);
		/*\Log::info('Created user with UUID: ' . $uuid );*/
    }
}
