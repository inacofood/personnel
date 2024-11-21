<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersWorkOrder extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "users";
    protected $guarded = [];
}
