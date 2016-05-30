<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionUserWeblistTable extends Migration
{
	/**
	 * Run the migration to create the list to PermissionUserWeblist pivot table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permission_user_weblist', function (Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			
			//Create the permission id foreign key.
			$table->integer('permission')->unsigned();
			$table->foreign('permission')->references('id')->on('permissions');
			
			//Create the user id foreign key.
			$table->string('usersid');
			$table->foreign('usersid')->references('userid')->on('users');
			
			//Create the list id foreign key.
			$table->integer('weblist_id')->unsigned();
			$table->foreign('weblist_id')->references('id')->on('weblists');
		});
	}

	/**
	 * Reverse the migration so that the list to PermissionUserWeblist pivot table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permission_user_weblist');
	}
}
