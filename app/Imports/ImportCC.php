<?php

namespace App\Imports;

use App\Models\CostCenter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportCC implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    // public function collection(Collection $collection)
    // {
       
    // }
    public function model(array $row){
        return new CostCenter([
            'name_cc' => $row['nama'],
            'code_cc' => $row['costcenter'],
        ]);
    }

    public function rules():array{
        return [
            'nama' => 'reuiqred',
            'costcenter' => 'required',
        ];
    }
    
    public function sheets():array{
        // return [
        //     'Input' => $this,
        // ];
    }
}
