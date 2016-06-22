<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Listitem extends Model
{
	//Change the primary key to listitemid instead of just id.
	protected $primaryKey = 'listitemid';

	public function weblist()
	{
		return $this->belongsToMany('\ListApp\Weblist')->withTimestamps();
	}
	public function tags()
	{
		return $this->belongsToMany('\ListApp\Tag')->withTimestamps();
	}
}
