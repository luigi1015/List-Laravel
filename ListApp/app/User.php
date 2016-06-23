<?php

namespace ListApp;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'userid', 'active',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	//Change the primary key to userid instead of just id.
	protected $primaryKey = 'userid';

	//Tell Laravel that the primary key isn't an incrementing integer, since otherwise it'll assume that.
	public $incrementing = false;

	public function permissions()
	{
		return $this->belongsToMany('\ListApp\Permission')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\ListApp\Permissionuserweblist')->withTimestamps();
	}
}
