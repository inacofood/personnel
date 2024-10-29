<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanSewa extends Model
{
    protected $table = 'kendaraan_sewa';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_sewa'; 

}



