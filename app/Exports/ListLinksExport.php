<?php

namespace App\Exports;

use App\Models\Emodule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ListLinksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Emodule::all()->map(function ($item) {
            unset($item['id']);
            unset($item['created_at']);
            unset($item['updated_at']);
 
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
