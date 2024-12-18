<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRole extends Model
{
    protected $table = 'users_role';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_users_role'; 

    public function user()
    {
        return $this->belongsTo(Users::class, 'id_users');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}



