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
            $table->string('permissionid')->primary();
			$table->timestamps();
			$table->string('title');
			$table->boolean('canEdit')->default(false);
			$table->boolean('canRead')->default(false);
			$table->boolean('canCreate')->default(false);
			$table->boolean('canDelete')->default(false);
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
