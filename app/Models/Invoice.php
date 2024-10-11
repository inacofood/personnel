<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $connection = 'mysqlmonitoring_invoice';
    protected $table = "invoice";
    protected $guarded = [];
}
