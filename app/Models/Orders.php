<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "orders";
    protected $guarded = [];
}
