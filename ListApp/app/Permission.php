<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	//Change the primary key to permissionid instead of just id.
	protected $primaryKey = 'permissionid';

	public function weblists()
	{
		return $this->belongsToMany('\ListApp\Weblist')->withTimestamps();
	}

	public function users()
	{
		return $this->belongsToMany('\ListApp\User')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\ListApp\Permissionuserweblist')->withTimestamps();
	}
}
