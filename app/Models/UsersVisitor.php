<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersVisitor extends Model
{
    protected $connection = 'mysqlvisitor';
    protected $table = "users";
    protected $guarded = [];
}
