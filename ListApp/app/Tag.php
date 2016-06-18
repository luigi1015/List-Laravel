<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	public function listItems()
	{
		return $this->belongsToMany('\ListApp\Listitem')->withTimestamps();
	}
}
