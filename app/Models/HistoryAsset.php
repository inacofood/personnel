<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryAsset extends Model
{
    protected $table = 'history_asset';
    protected $guarded = ['id'];
    protected $primaryKey = 'id_history_asset';

    public function kendaraan()
    {
        return $this->belongsTo(KendaraanAsset::class, 'id_asset', 'id_asset');
    }
}




