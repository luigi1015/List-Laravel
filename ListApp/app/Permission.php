<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	public function weblists()
	{
		return $this->belongsToMany('\App\Weblist')->withTimestamps();
	}

	public function users()
	{
		return $this->belongsToMany('\App\User')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\App\Permissionuserweblist')->withTimestamps();
	}
}
