<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListitemWeblistTable extends Migration
{
	/**
	 * Run the migration to create the list to listitems pivot table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listitem_weblist', function (Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			
			//Create the list id foreign key.
			$table->integer('weblist_id')->unsigned();
			$table->foreign('weblist_id')->references('id')->on('weblists')->onDelete('cascade');
			
			//Create the listItem id foreign key.
			$table->integer('listItem_id')->unsigned();
			$table->foreign('listItem_id')->references('id')->on('listitems')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migration so that the list to listitems pivot table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('listitem_weblist');
	}
}
