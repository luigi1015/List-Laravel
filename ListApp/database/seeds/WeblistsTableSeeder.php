<?php

use Illuminate\Database\Seeder;

class WeblistsTableSeeder extends Seeder
{
	/**
	 * Run the database seed to populate the lists table.
	 *
	 * @return void
	 */
	public function run()
	{
		$listTitles = ['List 01','List 02','List 03','List 04','List 05','List 06','List 07'];

		foreach( $listTitles as $listTitle )
		{
			$list = new \App\Weblist();
			$list->title = $listTitle;
			$list->save();
		}
	}
}
