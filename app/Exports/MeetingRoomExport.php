<?php

namespace App\Exports;

use App\Models\MeetingRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MeetingRoomExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = MeetingRoom::query();

        if ($this->dateStart && $this->dateEnd) {
            $query->whereBetween('date_start', [$this->dateStart, $this->dateEnd]);
        }

        return $query->select('date_start', 'time_start', 'time_end', 'room', 'pic', 'note', 'ket')->get();
    }

    public function headings(): array
    {
        return [
            'Date Start',
            'Time Start',
            'Time End',
            'Room',
            'PIC',
            'Note',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->date_start ? \Carbon\Carbon::parse($row->date_start)->format('d-m-Y') : '-',
            $row->time_start ?? '-',
            $row->time_end ?? '-',
            $row->room ?? '-',
            $row->pic ?? '-',
            $row->note ?? '-',
            $row->ket ?? '-',
        ];
    }
}
