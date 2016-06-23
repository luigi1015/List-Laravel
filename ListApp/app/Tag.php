<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	//Change the primary key to tagid instead of just id.
	protected $primaryKey = 'tagid';

	//Tell Laravel that the primary key isn't an incrementing integer, since otherwise it'll assume that.
	public $incrementing = false;

	public function listItems()
	{
		return $this->belongsToMany('\ListApp\Listitem')->withTimestamps();
	}
}
