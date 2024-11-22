<?php

namespace App\Exports;

use App\Models\Lists;
use App\Controllers\VisitorController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitorExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Mengambil data dari model Complaint
     */
    public function collection()
    {
        return Lists::select('jenis','nama_tamu', 'alamat', 'jumlah', 'bertemu_dengan', 'tujuan', 'masuk', 'keluar', 'status')->get();
    }

    /**
     * Menentukan header kolom untuk file Excel
     */
    public function headings(): array
    {
        return [
            'Jenis',
            'Nama Tamu',
            'Alamat',
            'Jumlah',
            'Bertemu dengan',
            'Tujuan',
            'Masuk',
            'Keluar',
            'Status'
        ];
    }

    /**
     * Memetakan data dari collection ke format yang sesuai untuk diekspor
     */
    public function map($row): array
    {
        return [
            $row->jenis,
            $row->nama_tamu,
            $row->alamat,
            $row->jumlah,
            $row->bertemu_dengan,
            $row->tujuan,
            $row->masuk,
            $row->keluar,
            $row->status,
           
        ];
    }
}
