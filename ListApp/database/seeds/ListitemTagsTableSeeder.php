<?php

use Illuminate\Database\Seeder;

class ListitemTagsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the ListitemTags table.
	 *
	 * @return void
	 */
	public function run()
	{
		$tagDescriptions = ['Tag 01','Tag 02','Tag 03','Tag 04','Tag 05','Tag 06','Tag 07'];

		$listItem = \App\Listitem::where('description','like','List 01 - Item 01')->first();

		foreach( $tagDescriptions as $tagDescription )
		{
			$tag = \App\Tag::where('description','like',$tagDescription)->first();
			//$listItem->tags()->save( $tag );
			DB::insert('INSERT INTO listitem_tag (created_at, updated_at, listItem_id, tag_id) VALUES (NOW(), NOW(), ?, ?)', [$listItem->listitemid, $tag->tagid]);
		}
	}
}
