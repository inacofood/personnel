<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "approval";
    protected $guarded = [];
}
