<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalMaterial extends Model
{
    protected $connection = 'mysqlwork_order';
    protected $table = "approval_material";
    protected $guarded = [];
}
