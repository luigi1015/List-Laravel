<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listitem extends Model
{
	public function weblist()
	{
		return $this->belongsToMany('\App\Weblist')->withTimestamps();
	}
	public function tags()
	{
		return $this->belongsToMany('\App\Tag')->withTimestamps();
	}
}
