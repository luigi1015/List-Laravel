<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
	/**
	 * Run the migration to create the tags table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function (Blueprint $table)
		{
			$table->string('tagid')->primary();
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
		Schema::drop('tags');
	}
}
