<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pettycash extends Model
{
    protected $connection = 'mysqlpettycash';
    protected $table = "pettycash";
    protected $fillable = [
        'tgl',
        'uraian',
        'kategori_id',
        'qty',
        'stn',
        'harga_stn',
        'total',
        'cost_center_id',
        'ket'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kat');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id', 'id_cc');
    }
}
