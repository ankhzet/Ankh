<?php

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Ankh\Entity;

class User extends Entity implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/** For soft deletion. */
	protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = array('name', 'email', 'password', 'roles');

	public function roles() {
		return $this->belongsToMany('Role');
	}

	public function isAdmin() {
		return \Cache::remember("u_{$this->id}_roles", 5, function () {
		  return $this->roles->contains(Role::find(Role::ADMIN));
		 });
	}

	public static function isUserAdmin() {
		$user = Auth::user();
		return $user ? $user->isAdmin() : false;
	}

}
