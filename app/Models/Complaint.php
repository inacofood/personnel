<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $connection = 'mysqlsafetyvoice';
    protected $table = "complaint";
    protected $guarded = [];
}
