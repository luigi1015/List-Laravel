<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListitemsTable extends Migration
{
	/**
	 * Run the migration to create the listitems table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listitems', function (Blueprint $table)
		{
			$table->string('listitemid')->primary();
			$table->timestamps();
			$table->string('description')->default('')->nullable(false);
			$table->boolean('checked')->default(false)->nullable(false);
		});
	}

	/**
	 * Reverse the migration so that the listitems table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('listitems');
	}
}
