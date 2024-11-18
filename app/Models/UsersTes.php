<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTes extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "users_tes";
    protected $guarded = [];
}
