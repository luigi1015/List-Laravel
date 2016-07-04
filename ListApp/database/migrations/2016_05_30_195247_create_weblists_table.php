<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeblistsTable extends Migration
{
	/**
	 * Run the migration to create the lists table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('weblists', function (Blueprint $table)
		{
			$table->string('weblistid')->primary();
			$table->timestamps();
			$table->string('title');
			$table->string('nameid')->unique();//An ID to use in stuff that needs an ID but should be human readable, like a URL.
			$table->boolean('public')->default(false)->nullable(false);
		});
	}

	/**
	 * Reverse the migration so that the lists table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('weblists');
	}
}
