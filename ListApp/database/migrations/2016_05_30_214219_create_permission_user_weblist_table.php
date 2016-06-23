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
			//$table->integer('permissionid')->unsigned();
			$table->string('permissionid');
			//$table->foreign('permission')->references('id')->on('permissions')->onDelete('cascade');
			$table->foreign('permissionid')->references('permissionid')->on('permissions')->onDelete('cascade');
			
			//Create the user id foreign key.
			$table->string('usersid');
			$table->foreign('usersid')->references('userid')->on('users')->onDelete('cascade');
			
			//Create the list id foreign key.
			$table->string('weblistid');
			$table->foreign('weblistid')->references('weblistid')->on('weblists')->onDelete('cascade');
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
