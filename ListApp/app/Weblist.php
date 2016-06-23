<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Weblist extends Model
{
	//Change the primary key to weblistid instead of just id.
	protected $primaryKey = 'weblistid';

	//Tell Laravel that the primary key isn't an incrementing integer, since otherwise it'll assume that.
	public $incrementing = false;

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
