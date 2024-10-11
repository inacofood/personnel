<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PettycashIn extends Model
{
    protected $connection = 'mysqlpettycash';
    protected $table = "pettycash_in";
    protected $fillable = [
        'tgl',
        'uraian',
        'total',
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
