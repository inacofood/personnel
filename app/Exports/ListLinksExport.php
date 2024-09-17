<?php

namespace App\Exports;

use App\Models\ListLink;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ListLinksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ListLink::all()->map(function ($item) {
            // Menghapus kolom created_at dan updated_at
            unset($item['id']);
            unset($item['created_at']);
            unset($item['updated_at']);
            // return $item;

            // Mengatur ulang urutan kolom
            return [
                $item['category'],
                $item['sub_cat'],
                $item['title'],
                $item['status'],
                $item['link'],
                $item['video'],
            ];

        });
    }

    public function headings(): array
    {
        return [
            'Category',
            'Sub-category',
            'Title',
            'Status',
            'Link',
            'Video',
        ];
    }
}
