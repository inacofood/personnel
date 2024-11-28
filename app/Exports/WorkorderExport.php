<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkorderExport implements FromCollection, WithHeadings
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Orders::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->select('created_at', 'updated_at', 'name', 'area', 'status','unsafe_envi')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Input',
            'Tanggal Perubahan Status',
            'Nama Inputer',
            'Lokasi Temuan',
            'Status',
            'Temuan Keadaan'
        ];
    }
}
