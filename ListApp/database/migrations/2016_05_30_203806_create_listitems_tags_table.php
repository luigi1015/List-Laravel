<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListitemsTagsTable extends Migration
{
	/**
	 * Run the migration to create the list to listitems pivot table.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listitem_tag', function (Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			
			//Create the listItem id foreign key.
			$table->integer('listItem_id')->unsigned();
			$table->foreign('listItem_id')->references('id')->on('listitems');
			
			//Create the list id foreign key.
			$table->integer('tag_id')->unsigned();
			$table->foreign('tag_id')->references('id')->on('tags');
		});
	}

	/**
	 * Reverse the migration so that the list to listitems pivot table is deleted.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('listitem_tag');
	}
}
