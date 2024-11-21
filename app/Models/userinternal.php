<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userinternl extends Model
{
    protected $connection = 'mysqlvisitor';
    protected $table = "userinternal";
    protected $guarded = [];
}
