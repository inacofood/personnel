<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $connection = 'mysqlpettycash';
    protected $table = "cost_center";
    protected $fillable = [
        'code_cc', 'name_cc',
    ];
}
