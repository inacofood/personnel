<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarGA extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "gambar_ga";
    protected $guarded = [];
}
