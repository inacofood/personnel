<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "items";
    protected $guarded = [];
}
