<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Listitem extends Model
{
	public function weblist()
	{
		return $this->belongsToMany('\ListApp\Weblist')->withTimestamps();
	}
	public function tags()
	{
		return $this->belongsToMany('\ListApp\Tag')->withTimestamps();
	}
}
