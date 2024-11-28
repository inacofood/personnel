<?php

namespace App\Exports;

use App\Models\Lists;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitorExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $jenis;
    protected $status;

    public function __construct($jenis = 'All', $status = 'All')
    {
        $this->jenis = $jenis;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Lists::query();

        if ($this->jenis !== 'All') {
            $query->where('jenis', $this->jenis);
        }

        if ($this->status !== 'All') {
            $query->where('status', $this->status);
        }

        return $query->select('jenis', 'nama_tamu', 'alamat', 'jumlah', 'bertemu_dengan', 'tujuan', 'masuk', 'keluar', 'status')->get();
    }

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
            'Status',
        ];
    }

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