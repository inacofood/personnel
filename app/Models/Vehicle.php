<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $connection = 'mysqlreservation';
    protected $table = "vehicle";
    protected $guarded = [];
}
