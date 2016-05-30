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
			$table->increments('id');
			$table->timestamps();
			$table->string('description');
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
