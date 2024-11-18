<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "logs";
    protected $guarded = [];
}
