<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Listitem extends Model
{
	//Change the primary key to listitemid instead of just id.
	protected $primaryKey = 'listitemid';

	//Tell Laravel that the primary key isn't an incrementing integer, since otherwise it'll assume that.
	public $incrementing = false;

	public function weblist()
	{
		return $this->belongsToMany('\ListApp\Weblist')->withTimestamps();
	}
	public function tags()
	{
		return $this->belongsToMany('\ListApp\Tag')->withTimestamps();
	}

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'listitemid', 'pivot', 'created_at', 'updated_at'
	];
}
