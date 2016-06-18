<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Permissionuserweblist extends Model
{
	public function weblists()
	{
		return $this->belongsTo('\ListApp\Weblist')->withTimestamps();
	}

	public function permissions()
	{
		return $this->belongsTo('\ListApp\Permission')->withTimestamps();
	}

	public function users()
	{
		return $this->belongsTo('\ListApp\User')->withTimestamps();
	}
}
