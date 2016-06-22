<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Weblist extends Model
{
	//Change the primary key to weblistid instead of just id.
	protected $primaryKey = 'weblistid';

	public function listitems()
	{
		return $this->belongsToMany('\ListApp\Listitem')->withTimestamps();
	}

	public function permissions()
	{
		return $this->belongsToMany('\ListApp\Permission')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\ListApp\Permissionuserweblist')->withTimestamps();
	}
}
