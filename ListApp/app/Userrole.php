<?php

namespace ListApp;

use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'userroleid', 'name', 'canEdit', 'canRead', 'canCreate', 'canDelete',
	];

	//Change the primary key to userroleid instead of just id.
	protected $primaryKey = 'userroleid';

	//Tell Laravel that the primary key isn't an incrementing integer, since otherwise it'll assume that.
	public $incrementing = false;

	public function users()
	{
		return $this->hasMany('\ListApp\User');
	}
}
