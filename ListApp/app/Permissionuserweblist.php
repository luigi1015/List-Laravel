<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissionuserweblist extends Model
{
	public function weblists()
	{
		return $this->belongsTo('\App\Weblist')->withTimestamps();
	}

	public function permissions()
	{
		return $this->belongsTo('\App\Permission')->withTimestamps();
	}

	public function users()
	{
		return $this->belongsTo('\App\User')->withTimestamps();
	}
}
