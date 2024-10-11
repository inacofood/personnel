<?php

namespace App\Exports;

use App\Models\PettycashIn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PettycashInExport implements FromCollection, WithHeadings
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
        // Menggunakan Eloquent untuk query data pettycash_in
        $query = PettycashIn::select('tgl', 'uraian', 'total', 'ket');

        // Filter berdasarkan rentang tanggal jika disediakan
        if ($this->params->start_date && $this->params->end_date) {
            $query->whereBetween('tgl', [
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
            'Total',
            'Keterangan',
        ];
    }
}
