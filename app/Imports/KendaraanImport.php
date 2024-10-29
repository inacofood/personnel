<?php

namespace App\Imports;

use App\Models\KendaraanAsset;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KendaraanImport implements ToModel
{
    public function model(array $row)
    {
        return new KendaraanAsset([
            'plat_no' => $row[1] ?? null,
            'nik' => $row[2] ?? null,
            'nama' => $row[3] ?? null,
            'lokasi' => $row[4] ?? null,
            'cc' => $row[5] ?? null,
            'nama_cc' => $row[6] ?? null,
            'dept' => $row[7] ?? null,
            'grade_title' => $row[8] ?? null,
            'merk' => $row[9] ?? null,
            'tipe' => $row[10] ?? null,
            'tahun' => $row[11] ?? null,
            'jenis' => $row[12] ?? null,
            'warna' => $row[13] ?? null,
            'kategori' => $row[14] ?? null,
            'no_rangka' => $row[15] ?? null,
            'no_mesin' => $row[16] ?? null,
            'no_bpkb' => $row[17] ?? null,
            'asuransi_start_date' => isset($row[18]) && is_numeric($row[18]) ? Carbon::instance(Date::excelToDateTimeObject($row[18]))->format('Y-m-d') : null,
            'asuransi_end_date' => isset($row[19]) && is_numeric($row[19]) ? Carbon::instance(Date::excelToDateTimeObject($row[19]))->format('Y-m-d') : null,
            'vendor_asuransi' => $row[20] ?? null,
            'no_polis_asuransi' => $row[21] ?? null,
            'premi_asuransi' => $row[22] ?? null,
            'tahunan_start' => isset($row[23]) && is_numeric($row[23]) ? Carbon::instance(Date::excelToDateTimeObject($row[23]))->format('Y-m-d') : null,
            'tahunan_end' => isset($row[24]) && is_numeric($row[24]) ? Carbon::instance(Date::excelToDateTimeObject($row[24]))->format('Y-m-d') : null,
            'lima_tahunan_start' => isset($row[25]) && is_numeric($row[25]) ? Carbon::instance(Date::excelToDateTimeObject($row[25]))->format('Y-m-d') : null,
            'lima_tahunan_end' => isset($row[26]) && is_numeric($row[26]) ? Carbon::instance(Date::excelToDateTimeObject($row[26]))->format('Y-m-d') : null,
            'keterangan' => $row[27] ?? null,
        ]);
    }
}
