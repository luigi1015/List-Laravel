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
			$table->string('listItem_id');
			$table->foreign('listItem_id')->references('listitemid')->on('listitems')->onDelete('cascade');
			
			//Create the list id foreign key.
			$table->string('tag_id');
			$table->foreign('tag_id')->references('tagid')->on('tags')->onDelete('cascade');
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
