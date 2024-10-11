<?php

namespace App\Exports;

use App\Models\Pettycash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PettycashExport implements FromCollection, WithHeadings
{
    /**
    * @var object
    */
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        // Menggunakan Eloquent untuk query data pettycash dengan join ke tabel kategori dan cost_center
        $query = Pettycash::select(
            'pettycash.tgl',
            'pettycash.uraian',
            'kategori.name_kat',
            'pettycash.qty',
            'pettycash.stn',
            'pettycash.harga_stn',
            'pettycash.total',
            'cost_center.code_cc AS cost_center',
            'pettycash.ket'
        )
        ->leftJoin('kategori', 'pettycash.kategori_id', '=', 'kategori.id_kat')
        ->leftJoin('cost_center', 'pettycash.cost_center_id', '=', 'cost_center.id_cc');

        // Filter berdasarkan rentang tanggal jika disediakan
        if ($this->params->start_date && $this->params->end_date) {
            $query->whereBetween('pettycash.tgl', [
                "{$this->params->start_date} 00:00:00",
                "{$this->params->end_date} 23:59:59"
            ]);
        }

        // Mengembalikan hasil query sebagai collection
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Uraian',
            'Kategori',
            'Qty',
            'Satuan',
            'Harga Satuan',
            'Total',
            'Cost Center',
            'Keterangan',
        ];
    }
}
