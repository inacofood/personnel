<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KendaraanSewa;
use App\Models\KendaraanAsset;
use App\Imports\KendaraanImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class KendaraanController extends Controller
{
    public function indexasset()
    {
        $kendaraan = KendaraanAsset::all(); 
       
        return view('kendaraan.kendaraanasset', compact('kendaraan')); 
    }

    public function indexsewa()
    {
        $kendaraan = KendaraanSewa::all();
        return view('kendaraan.kendaraansewa', compact('kendaraan')); 
    }

    // IMPORT KENDARAAN ASSET
    public function importKendaraan(Request $request) 
    {
        set_time_limit(300);

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
    
        $file = $request->file('file');
        $filePath = $file->getRealPath();
    
        try {
            $spreadsheet = IOFactory::load($filePath);
    
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $sheet->removeRow(1, 1);
    
                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); 
    
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getFormattedValue();
                    }

                    if (!empty($rowData[18])) {
                        if (is_numeric($rowData[18])) {
                            $asuransiStartDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[18]))->format('Y-m-d');
                        } else {
                            try {
                                $asuransiStartDate = Carbon::createFromFormat('d/M/y', $rowData[18])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format asuransi start date tidak valid: " . $rowData[18]);
                                $asuransiStartDate = null; 
                            }
                        }
                    }

                    if (!empty($rowData[19])) {
                        if (is_numeric($rowData[19])) {
                            $asuransiEndDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[19]))->format('Y-m-d');
                        } else {
                            try {
                                $asuransiEndDate = Carbon::createFromFormat('d/M/y', $rowData[19])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format asuransi end date tidak valid: " . $rowData[19]);
                                $asuransiEndDate = null;
                            }
                        }
                    }
                    
                    if (!empty($rowData[23])) {
                        if (is_numeric($rowData[23])) {
                            $tahunanStart = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[23]))->format('Y-m-d');
                        } else {
                            try {
                                $tahunanStart = Carbon::createFromFormat('d-M-y', $rowData[23])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format 1 tahunan start date tidak valid: " . $rowData[23]);
                                $tahunanStart = null;
                            }
                        }
                    }
                    
                    if (!empty($rowData[24])) {
                        if (is_numeric($rowData[24])) {
                            $tahunanEnd = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[24]))->format('Y-m-d');
                        } else {
                            try {
                                $tahunanEnd = Carbon::createFromFormat('d-M-y', $rowData[24])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format 1 tahunan end date tidak valid: " . $rowData[24]);
                                $tahunanEnd = null;
                            }
                        }
                    }
                    
                    if (!empty($rowData[25])) {
                        if (is_numeric($rowData[25])) {
                            $limaTahunanStart = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[25]))->format('Y-m-d');
                        } else {
                            try {
                                $limaTahunanStart = Carbon::createFromFormat('d-M-y', $rowData[25])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format 5 tahunan start date tidak valid: " . $rowData[25]);
                                $limaTahunanStart = null;
                            }
                        }
                    }
                    
                    if (!empty($rowData[26])) {
                        if (is_numeric($rowData[26])) {
                            $limaTahunanEnd = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[26]))->format('Y-m-d');
                        } else {
                            try {
                                $limaTahunanEnd = Carbon::createFromFormat('d-M-y', $rowData[26])->format('Y-m-d');
                            } catch (\Exception $e) {
                                \Log::error("Format 5 tahunan end date tidak valid: " . $rowData[26]);
                                $limaTahunanEnd = null;
                            }
                        }
                    }
                    
                    $platNo = $rowData[1] ?? null;
                    $existingData = KendaraanAsset::where('plat_no', $platNo)->first();

                    $data = [
                        'plat_no' => $rowData[1] ?? null,
                        'nik' => !empty($rowData[2]) && is_numeric($rowData[2]) ? $rowData[2] : null,
                        'nama_karyawan' => $rowData[3] ?? null,
                        'lokasi' => $rowData[4] ?? null,
                        'cc' => $rowData[5] ?? null,
                        'cc_nama' => $rowData[6] ?? null,
                        'dept' => $rowData[7] ?? null,
                        'grade_title' => $rowData[8] ?? null,
                        'merk' => $rowData[9] ?? null,
                        'tipe' => $rowData[10] ?? null,
                        'tahun' => !empty($rowData[11]) && is_numeric($rowData[11]) ? $rowData[11] : null,
                        'jenis' => $rowData[12] ?? null,
                        'warna' => $rowData[13] ?? null,
                        'kategori' => $rowData[14] ?? null,
                        'no_rangka' => $rowData[15] ?? null,
                        'no_mesin' => $rowData[16] ?? null,
                        'no_bpkb' => $rowData[17] ?? null,
                        'asuransi_start_date' => $asuransiStartDate,
                        'asuransi_end_date' => $asuransiEndDate,
                        'vendor_asuransi' => $rowData[20] ?? null,
                        'no_polis_asuransi' => $rowData[21] ?? null,
                        'premi_asuransi' => $rowData[22] ?? null,
                        'satu_tahunan_start' =>  $tahunanStart,
                        'satu_tahunan_end' =>  $tahunanEnd,
                        'lima_tahunan_start' =>  $limaTahunanStart,
                        'lima_tahunan_end' =>  $limaTahunanEnd,
                        'ket' => $rowData[27] ?? null,
                    ];
                    if ($existingData) {
                        $existingData->update($data);
                    } else {
                        KendaraanAsset::create($data);
                    }
                }
            }
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    
        return redirect()->back()->with('alert', [
            'msg' => 'Berhasil menambahkan data kendaraan'
        ]);
    }

    public function importKendaraanSewa(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(300);
    
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
    
        $file = $request->file('file');
        $filePath = $file->getRealPath();
    
        try {
    
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true); 
            $spreadsheet = $reader->load($filePath);
    
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
    
            $successCount = 0;
            $errorCount = 0;
    
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    $rowData = [];
                    for ($col = 'A'; $col <= 'W'; $col++) { 
                        $cell = $sheet->getCell($col . $row);
                        $rowData[] = $cell->getFormattedValue();
                    }

                    $masaSewaStart = $this->convertExcelDate($rowData[16], 'masa sewa start', $row);
                    $masaSewaEnd = $this->convertExcelDate($rowData[17], 'masa sewa end', $row);
                    $endDateHEmpatLima = $this->convertExcelDate($rowData[18], 'end date h_45', $row);
                    $platNo = $rowData[0] ?? null;
    
                    if (!$platNo) {
                        \Log::warning("Baris $row: Plat No kosong. Melewati baris ini.");
                        continue; 
                    }
    
                    KendaraanSewa::updateOrCreate(
                        ['plat_no' => $platNo],
                        [
                            'nik' => (!empty($rowData[1]) && is_numeric($rowData[1])) ? $rowData[1] : null,
                            'nama_karyawan' => $rowData[2] ?? null,
                            'lokasi' => $rowData[3] ?? null,
                            'cc' => $rowData[4] ?? null,
                            'cc_nama' => $rowData[5] ?? null,
                            'departemen' => $rowData[6] ?? null,
                            'vendor' => $rowData[7] ?? null,
                            'grade_title' => $rowData[8] ?? null,
                            'no_tlp' => $rowData[9] ?? null,
                            'merk' => $rowData[10] ?? null,
                            'tipe' => $rowData[11] ?? null,
                            'tahun' => $rowData[12] ?? null,
                            'jenis' => $rowData[13] ?? null,
                            'harga_sewa' => $rowData[14] ?? null,
                            'harga_sewa_ppn' => $rowData[15] ?? null,
                            'masa_sewa_start' => $masaSewaStart,
                            'masa_sewa_end' => $masaSewaEnd,
                            'end_date_h_empatlima' => $endDateHEmpatLima,
                            'alert_masa_sewa' => $rowData[19] ?? null,
                            'status' => $rowData[20] ?? null,
                            'note_to_do' => $rowData[21] ?? null,
                            'ket' => $rowData[22] ?? null,
                        ]
                    );
    
                    $successCount++; 
                } catch (\Exception $e) {
                    \Log::error("Baris $row: Error saat menyimpan data - " . $e->getMessage());
                    $errorCount++;
                    continue;
                }
            }
  
            \Log::info("Import selesai: $successCount data berhasil, $errorCount data gagal.");
    
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            \Log::error('Excel Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    
        return redirect()->back()->with('alert', [
            'msg' => "Berhasil menambahkan $successCount data kendaraan sewa, $errorCount data gagal."
        ]);
    }
    
    private function convertExcelDate($value, $fieldName, $row)
    {
        if (empty($value)) {
            return null;
        }
    
        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            } else {
                return Carbon::createFromFormat('d-M-y', $value)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            \Log::error("Baris $row: Format $fieldName tidak valid: " . $value);
            return null;
        }
    }

    public function updatesewa(Request $request)
    {
       
        $sewa = KendaraanSewa::findOrFail($request->id);

        $masa_sewa_start = \Carbon\Carbon::parse($request->masa_sewa_start)->format('Y-m-d');
        $masa_sewa_end = \Carbon\Carbon::parse($request->masa_sewa_end)->format('Y-m-d');
        $end_date_h_empatlima = \Carbon\Carbon::parse($request->end_date_h_empatlima)->format('Y-m-d');
    
        $sewa->update([
            'plat_no' => $request->plat_no,
            'nik' => $request->nik,
            'nama_karyawan' => $request->nama_karyawan,
            'lokasi' => $request->lokasi,
            'cc' => $request->cc,
            'cc_nama' => $request->cc_nama,
            'departemen' => $request->departemen,
            'vendor' => $request->vendor,
            'grade_title' => $request->grade_title,
            'no_tlp' => $request->no_tlp,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
            'jenis' => $request->jenis,
            'harga_sewa' => $request->harga_sewa,
            'harga_sewa_ppn' => $request->harga_sewa_ppn,
            'masa_sewa_start' => $masa_sewa_start,
            'masa_sewa_end' => $masa_sewa_end,
            'end_date_h_empatlima' => $end_date_h_empatlima,
            'alert_masa_sewa' => $request->alert_masa_sewa,
            'status' => $request->status,
            'note_to_do' => $request->note_to_do,
            'ket' => $request->ket,
        ]);
    
        return redirect()->back()->with('success', 'Data Sewa berhasil diperbarui.');
    }
    
    
public function updateuser(Request $request)
{
    $validated = $request->validate([
        'nama_karyawan' => 'required|string|max:255',
        'departemen' => 'required|string|max:255',
    ]);

    $sewa = KendaraanSewa::findOrFail($request->id);
    $sewa->update([
        'nama_karyawan' => $request->nama_karyawan,
        'departemen' => $request->departemen,
    ]);

    return redirect()->back()->with('success', 'User updated successfully.');
}


public function perpanjangsewa(Request $request)
{
    $validated = $request->validate([
        'nama_karyawan' => 'required|string|max:255',
        'departemen' => 'required|string|max:255',
        'masa_sewa_start' => 'required|string|max:255',
        'masa_sewa_end' => 'required|string|max:255',
    ]);

    $sewa = KendaraanSewa::findOrFail($request->id);
    $sewa->update([
        'nama_karyawan' => $request->nama_karyawan,
        'departemen' => $request->departemen,
        'masa_sewa_start' => $request->masa_sewa_start,
        'masa_sewa_end' => $request->masa_sewa_end,
    ]);

    return redirect()->back()->with('success', 'Data Sewa berhasil diperbarui.');
}

public function createsewa(Request $request)
{

    KendaraanSewa::create([
            'plat_no' => $request->plat_no,
            'nik' => $request->nik,
            'nama_karyawan' => $request->nama_karyawan,
            'lokasi' => $request->lokasi,
            'cc' => $request->cc,
            'cc_nama' => $request->cc_nama,
            'departemen' => $request->departemen,
            'vendor' => $request->vendor,
            'grade_title' => $request->grade_title,
            'no_tlp' => $request->no_tlp,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
            'jenis' => $request->jenis,
            'harga_sewa' => $request->harga_sewa,
            'harga_sewa_ppn' => $request->harga_sewa_ppn,
            'masa_sewa_start' =>  $request->masa_sewa_start,
            'masa_sewa_end' => $request->masa_sewa_end,
            'end_date_h_empatlima' =>  $request->end_date_h_empatlima,
            'alert_masa_sewa' => $request->alert_masa_sewa,
            'status' => $request->status,
            'note_to_do' => $request->note_to_do,
            'ket' => $request->ket,
    ]);

    return redirect()->back()->with('success', 'Data Sewa berhasil ditambahkan.');
}


public function updateasset(Request $request)
{
  
    $asset = KendaraanAsset::findOrFail($request->id);
   
    $asset->update([
        'plat_no' => $request->plat_no,
        'nik' => $request->nik,
        'nama_karyawan' => $request->nama_karyawan,
        'lokasi' => $request->lokasi,
        'cc' => $request->cc,
        'cc_nama' => $request->cc_nama,
        'dept' => $request->dept,
        'grade_title' => $request->grade_title,
        'merk' => $request->merk,
        'tipe' => $request->tipe,
        'tahun' => $request->tahun,
        'jenis' => $request->jenis,
        'warna' => $request->warna,
        'kategori' => $request->kategori,
        'no_rangka' => $request->no_rangka,
        'no_mesin' => $request->no_mesin,
        'no_bpkb' => $request->no_bpkb,
        'no_polis_asuransi' => $request->no_polis_asuransi,
        'premi_asuransi' => $request->premi_asuransi,
        'vendor_asuransi' => $request->vendor_asuransi,
        'asuransi_start_date' => $request->asuransi_start_date,
        'asuransi_end_date' => $request->asuransi_end_date,
        'satu_tahunan_start' => $request->satu_tahunan_start,
        'satu_tahunan_end' => $request->satu_tahunan_end,
        'lima_tahunan_start' => $request->lima_tahunan_start,
        'lima_tahunan_end' => $request->lima_tahunan_end,
        'ket' => $request->ket,
    ]);
   
    return redirect()->back()->with('success', 'Asset data updated successfully!');
}

public function createasset(Request $request)
{
    KendaraanAsset::create([
        'plat_no' => $request->plat_no,
        'nik' => $request->nik,
        'nama_karyawan' => $request->nama_karyawan,
        'lokasi' => $request->lokasi,
        'cc' => $request->cc,
        'cc_nama' => $request->cc_nama,
        'dept' => $request->dept,
        'grade_title' => $request->grade_title,
        'merk' => $request->merk,
        'tipe' => $request->tipe,
        'tahun' => $request->tahun,
        'jenis' => $request->jenis,
        'warna' => $request->warna,
        'kategori' => $request->kategori,
        'no_rangka' => $request->no_rangka,
        'no_mesin' => $request->no_mesin,
        'no_bpkb' => $request->no_bpkb,
        'no_polis_asuransi' => $request->no_polis_asuransi,
        'premi_asuransi' => $request->premi_asuransi,
        'vendor_asuransi' => $request->vendor_asuransi,
        'asuransi_start_date' => $request->asuransi_start_date,
        'asuransi_end_date' => $request->asuransi_end_date,
        'satu_tahunan_start' => $request->satu_tahunan_start,
        'satu_tahunan_end' => $request->satu_tahunan_end,
        'lima_tahunan_start' => $request->lima_tahunan_start,
        'lima_tahunan_end' => $request->lima_tahunan_end,
        'ket' => $request->ket,
    ]);

    return redirect()->back()->with('success', 'New asset data created successfully!');
}


}