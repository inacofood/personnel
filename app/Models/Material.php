<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "material";
    protected $guarded = [];
}
