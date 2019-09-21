<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Append extra attributes to the User array
     *
     * @var array
     */
    protected $appends = [
        'role_name', 'role_key'
    ];

    /**
     * Get the Role associated with the user
     */
    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }

    /**
     * Get the Posts that are associated with the user
     */
    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    /**
     * Get User Role Name
     *
     * @return string|null
     */
    public function getRoleNameAttribute()
    {
        return $this->role !== null ? $this->role->name : null;
    }

    /**
     * Get User Role key
     *
     * @return string|null
     */
    public function getRoleKeyAttribute()
    {
        return $this->role !== null ? $this->role->key : null;
    }

}
