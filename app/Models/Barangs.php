<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangs extends Model
{
    protected $connection = 'mysqlvisitor';
    protected $table = "barangs";
    protected $guarded = [];
}
