<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	public function listItems()
	{
		return $this->belongsToMany('\App\Listitem')->withTimestamps();
	}
}
