<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    protected $connection = 'mysqlvisitor';
    protected $table = "lists";
    protected $guarded = [];
}
