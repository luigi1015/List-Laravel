<?php

namespace App;

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

	public function permissions()
	{
		return $this->belongsToMany('\App\Permission')->withTimestamps();
	}

	public function permissionuserweblists()
	{
		return $this->hasMany('\App\Permissionuserweblist')->withTimestamps();
	}
}
