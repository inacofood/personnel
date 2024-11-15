<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryUser extends Model
{
    protected $table = 'history_user';
    protected $guarded = ['id'];
    protected $primaryKey = 'id_history_user';

    public function kendaraanSewa()
{
    return $this->belongsTo(KendaraanSewa::class, 'id_sewa');
}
}



