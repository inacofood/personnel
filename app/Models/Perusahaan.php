<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $connection = 'mysqlmonitoring_invoice';
    protected $table = "perusahaan";
    protected $guarded = [];
}
