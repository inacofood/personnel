<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    protected $connection = 'mysqlvisitor';
    protected $table = "security";
    protected $guarded = [];
}
