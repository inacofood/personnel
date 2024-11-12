<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAsset extends Model
{
    protected $table = 'service_asset';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_service'; 

    public function kendaraan()
{
    return $this->belongsTo(KendaraanAsset::class, 'id_asset', 'id_asset');
}

}



