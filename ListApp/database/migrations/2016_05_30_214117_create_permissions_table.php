<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
	/**
	 * Run the migration to create the permissions table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function (Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer('permissionid')->unique();
			$table->string('title');
		});
	}

	/**
	 * Reverse the migration so that the permissions table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions');
	}
}