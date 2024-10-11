<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users'; 
    protected $fillable = ['id', 'name', 'email', 'password', 'department'];
    public $timestamps = true;

    public function role()
    {
        return $this->belongsTo(Role::class, 'department', 'id'); 
    }

    public function roles()
    {
    return $this->belongsToMany(Role::class, 'usersrole', 'id_users', 'id_role');
    }
}
