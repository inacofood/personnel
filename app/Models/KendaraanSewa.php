<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanSewa extends Model
{
    protected $table = 'kendaraan_sewa';
    protected $guarded = ['id_sewa'];
    protected $primaryKey = 'id_sewa';

    public function historySewa()
    {
        return $this->hasMany(HistorySewa::class, 'id_sewa');
    }
}



