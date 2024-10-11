<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = ['department_name']; 
    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(User::class, 'department', 'id'); 
    }

    public function usersRoles()
    {
        return $this->belongsToMany(UsersRole::class, 'usersrole', 'id_role', 'id_users'); 
    }
}

