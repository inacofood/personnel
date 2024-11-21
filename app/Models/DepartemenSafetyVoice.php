<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartemenSafetyVoice extends Model
{
    protected $connection = 'mysqlsafetyvoice';
    protected $table = "departements";
    protected $guarded = [];
}
