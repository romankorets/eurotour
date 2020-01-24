<?php

namespace App;

use App\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class, 'roles_user', 'userId', 'roleId');
    }

    public function hasRole($roleSlug) : bool {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    public function hasAccess(array $permissions) : bool {
        foreach ($this->roles as $role){
            if($role->hasAccess($permissions)){
                return true;
            }
        }
        return false;
    }
}
