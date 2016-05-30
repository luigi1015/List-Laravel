<?php

use Illuminate\Database\Seeder;

class ListitemsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the lists table.
	 *
	 * @return void
	 */
	public function run()
	{
		$listTitles = ['List 01','List 02','List 03','List 04','List 05','List 06','List 07'];
		$itemDescriptions = ['Item 01','Item 02','Item 03','Item 04','Item 05','Item 06','Item 07'];

		foreach( $listTitles as $listTitle )
		{
			foreach( $itemDescriptions as $itemDescription )
			{
				$listitem = new \App\Listitem();
				$listitem->description = $listTitle . ' - ' . $itemDescription;
				$listitem->save();
			}
		}
	}
}
