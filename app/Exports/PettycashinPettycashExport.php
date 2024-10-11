<?php

namespace App\Exports;

use App\Models\Pettycash;
use App\Models\PettycashIn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PettycashinPettycashExport implements FromCollection, WithHeadings
{
    protected $params;

    public function __construct(array $params)
    {
        $this->params = (object) $params;
    }

    public function collection()
    {
        // Query for 'Pengeluaran' from pettycash
        $pengeluaran = Pettycash::select(
            DB::raw("'Pengeluaran' AS jenis"),
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
        ->leftJoin('kategori', 'pettycash.kategori_id', '=', 'kategori.id_kat') // Sesuaikan dengan nama kolom yang benar
        ->leftJoin('cost_center', 'pettycash.cost_center_id', '=', 'cost_center.id_cc');

        // Query for 'Pemasukan' from pettycash_in
        $pemasukan = PettycashIn::select(
            DB::raw("'Pemasukan' AS jenis"),
            'pettycash_in.tgl',
            'pettycash_in.uraian',
            DB::raw('NULL AS name_kat'),
            DB::raw('NULL AS qty'),
            DB::raw('NULL AS stn'),
            DB::raw('NULL AS harga_stn'),
            'pettycash_in.total',
            DB::raw('NULL AS cost_center'),
            'pettycash_in.ket'
        );

        // Filtering by date range if provided
        if (!empty($this->params->start_date) && !empty($this->params->end_date)) {
            $pengeluaran->whereBetween('pettycash.tgl', [$this->params->start_date, $this->params->end_date]);
            $pemasukan->whereBetween('pettycash_in.tgl', [$this->params->start_date, $this->params->end_date]);
        }

        // Merge both queries using union
        return $pengeluaran->unionAll($pemasukan)->get();
    }

    public function headings(): array
    {
        return [
            'Jenis',
            'Tanggal',
            'Uraian',
            'Kategori',
            'Qty',
            'Satuan',
            'Harga Satuan',
            'Total',
            'Cost Center',
            'Keterangan'
        ];
    }
}
