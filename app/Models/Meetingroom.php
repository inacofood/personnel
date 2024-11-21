<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meetingroom extends Model
{
    protected $connection = 'mysqlreservation';
    protected $table = "meetingroom";
    protected $guarded = [];
}
