<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePresensi extends Model
{
    //use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $connection = 'mysqlhrms';
    protected $table = 'employee_presensi_bulanan';
    protected $primaryKey = 'id_presensi_bulanan';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_presensi_bulanan'];


}
