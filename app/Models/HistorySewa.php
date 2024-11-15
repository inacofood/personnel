<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorySewa extends Model
{
    protected $table = 'history_sewa';
    protected $guarded = ['id'];
    protected $primaryKey = 'id_history_sewa';

    public function kendaraanSewa()
{
    return $this->belongsTo(KendaraanSewa::class, 'id_sewa');
}
}



