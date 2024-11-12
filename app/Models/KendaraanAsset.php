<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanAsset extends Model
{
    protected $table = 'kendaraan_asset';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_asset'; 

    public function serviceAssets()
    {
    return $this->hasMany(ServiceAsset::class, 'id_asset', 'id_asset');
    }

    public function historyAssets()
    {
        return $this->hasMany(HistoryAsset::class, 'id_asset', 'id_asset');
    }

}



