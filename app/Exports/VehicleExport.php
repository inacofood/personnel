<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VehicleExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $dateStart;
    protected $dateEnd;

    public function __construct($dateStart = null, $dateEnd = null)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public function collection()
    {
        $query = Vehicle::query();

        if ($this->dateStart && $this->dateEnd) {
            $query->whereBetween('date_start', [$this->dateStart, $this->dateEnd]);
        }

        return $query->select('created_at', 'date_start', 'start_time', 'end_time', 'pic', 'dept', 'needs', 'driver', 'nama_driver', 'opsi', 'biaya')->get();
    }

    public function headings(): array
    {
        return [
            'Input Date',
            'Date',
            'Time Start',
            'Time End',
            'PIC',
            'Departemen',
            'Needs',
            'Driver',
            'Nama Driver',
            'Opsi Lain',
            'Biaya',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '-',
            $row->date_start ? \Carbon\Carbon::parse($row->date_start)->format('d-m-Y') : '-',
            $row->start_time ?? '-',
            $row->end_time ?? '-',
            $row->pic ?? '-',
            $row->dept ?? '-',
            $row->needs ?? '-',
            $row->driver ?? '-',
            $row->nama_driver ?? '-',
            $row->opsi ?? '-',
            $row->biaya ?? '-',
        ];
    }
}
