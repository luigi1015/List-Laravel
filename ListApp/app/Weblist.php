<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weblist extends Model
{
	public function listitems()
	{
		return $this->belongsToMany('\App\Listitem')->withTimestamps();
	}

	public function permissions()
	{
		return $this->belongsToMany('\App\Permission')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\App\Permissionuserweblist')->withTimestamps();
	}
}
