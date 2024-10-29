<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanAsset extends Model
{
    protected $table = 'kendaraan_asset';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_asset'; 

}



