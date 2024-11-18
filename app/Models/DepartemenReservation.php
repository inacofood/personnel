<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartemenReservation extends Model
{
    protected $connection = 'mysqlreservation';
    protected $table = "departemen";
    protected $guarded = [];
}
