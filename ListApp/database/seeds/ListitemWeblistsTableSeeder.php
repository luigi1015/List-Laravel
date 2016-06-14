<?php

use Illuminate\Database\Seeder;

class ListitemWeblistsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the list to listitems pivot table.
	 *
	 * @return void
	 */
	public function run()
	{
		$listTitles = ['List 01','List 02','List 03','List 04','List 05','List 06','List 07'];
		$itemDescriptions = ['Item 01','Item 02','Item 03','Item 04','Item 05','Item 06','Item 07'];

		foreach( $listTitles as $listTitle )
		{
			$weblist = \App\Weblist::where('title','like',$listTitle)->first();

			foreach( $itemDescriptions as $itemDescription )
			{
				$listItem = \App\Listitem::where('description','like',$listTitle . ' - ' . $itemDescription)->first();
				//$list->listitems()->save( $listItem );
				DB::insert('INSERT INTO listitem_weblist (created_at, updated_at, weblist_id, listitem_id) VALUES (NOW(), NOW(), ?, ?)', [$weblist->weblistid, $listItem->listitemid]);
			}
		}
	}
}
