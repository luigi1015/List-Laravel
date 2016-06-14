<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\ListAppController;

class TagsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the tags table.
	 *
	 * @return void
	 */
	public function run()
	{
		$tagDescriptions = ['Tag 01','Tag 02','Tag 03','Tag 04','Tag 05','Tag 06','Tag 07'];

		foreach( $tagDescriptions as $tagDescription )
		{
			$uuid = ListAppController::getUUID( 'tags', 'tagid' );
			$tag = new \App\Tag();
			$tag->tagid = $uuid;
			$tag->description = $tagDescription;
			$tag->save();
		}
	}
}
