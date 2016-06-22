<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	//Change the primary key to tagid instead of just id.
	protected $primaryKey = 'tagid';

	public function listItems()
	{
		return $this->belongsToMany('\ListApp\Listitem')->withTimestamps();
	}
}
