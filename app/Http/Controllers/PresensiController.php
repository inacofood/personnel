<?php

namespace App\Http\Controllers;

use App\Models\EmployeePresensi;
use App\Models\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Exports\PresensiExport;


class PresensiController extends Controller
{
    public function index()
    {
        $presensi = EmployeePresensi::all();

        return view('presensi.index', [
            'presensi' => $presensi
        ]);
    }

    public function import(Request $request)
{
    // Set batas waktu eksekusi menjadi 300 detik
    set_time_limit(300);
    
    $file = $request->file('file');
    $filePath = $file->getRealPath();
    $spreadsheet = IOFactory::load($filePath);

    try {
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheet->removeRow(1, 1); // Jika header ada di baris pertama
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getFormattedValue();
                }

                // Parsing kolom tanggal dan join_date
                $tanggal = null;
                $joinDate = null;

                // Kolom Tanggal (index 2)
                if (!empty($rowData[2])) {
                    if (is_numeric($rowData[2])) {
                        $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[2]))->format('Y-m-d');
                    } else {
                        try {
                            $tanggal = Carbon::createFromFormat('d-M-y', $rowData[2])->format('Y-m-d');
                        } catch (\Exception $e) {
                            \Log::error("Format tanggal tidak valid: " . $rowData[2]);
                        }
                    }
                }

                // Kolom Join Date (index 15)
                if (!empty($rowData[15])) {
                    if (is_numeric($rowData[15])) {
                        $joinDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData[15]))->format('Y-m-d');
                    } else {
                        try {
                            $joinDate = Carbon::createFromFormat('d-M-y', $rowData[15])->format('Y-m-d');
                        } catch (\Exception $e) {
                            \Log::error("Format join date tidak valid: " . $rowData[15]);
                        }
                    }
                }

                $nik = $rowData[0] ?? null;
                $existingData = EmployeePresensi::where('nik', $nik)
                                               ->where('tanggal', $tanggal)
                                               ->first();

                // Validasi waktu format untuk scan_masuk dan scan_pulang
                $scan_masuk = isset($rowData[5]) && preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $rowData[5]) ? $rowData[5] . ':00' : null;
                $scan_pulang = isset($rowData[6]) && preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $rowData[6]) ? $rowData[6] . ':00' : null;

                $data = [
                    'nik' => $nik,
                    'nama' => $rowData[1] ?? null,
                    'tanggal' => $tanggal,
                    'week' => $rowData[3] ?? null,
                    'jam_kerja' => $rowData[4] ?? null,
                    'scan_masuk' => $scan_masuk, // Scan masuk validasi
                    'scan_pulang' => $scan_pulang, // Scan pulang validasi
                    'terlambat' => $rowData[7] ? $rowData[7] . ':00' : null,
                    'pulang_cepat' => $rowData[8] ? $rowData[8] . ':00' : null,
                    'absent' => $rowData[9] ?? null,
                    'pengecualian' => $rowData[10] ?? null,
                    'HK' => !empty($rowData[11]) && is_numeric($rowData[11]) ? $rowData[11] : null, 
                    'dept' => $rowData[12] ?? null,
                    'section' => $rowData[13] ?? null,
                    'grade' => $rowData[14] ?? null,
                    'join_date' => $joinDate, // Join date validasi
                    'jt' => $rowData[16] ?? null,
                    'atasan' => $rowData[17] ?? null,
                    'created_by' => 6,
                ];

                if ($existingData) {
                    EmployeePresensi::where('nik', $nik)
                                    ->where('tanggal', $tanggal)
                                    ->update($data);
                } else {
                    EmployeePresensi::insert($data);
                }
            }
        }
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        return "Error: " . $e->getMessage();
    }

    return redirect()->back()->with('alert', [
        'msg' => 'Berhasil menambahkan Kehadiran'
    ]);
}

public function rekapPresensiBulanan()
{
    $datanama = DB::table('employee_presensi_bulanan')
    ->select('nama')
    ->distinct()
    ->get();

    $rekapKehadiran = DB::table('employee_presensi_bulanan')
        ->select(
            'nama', // Nama karyawan
            DB::raw('MONTH(tanggal) as bulan'), // Ambil bulan dari tanggal
            DB::raw('YEAR(tanggal) as tahun'), // Ambil tahun dari tanggal
            // Hitung total hadir berdasarkan adanya scan masuk atau scan pulang
            DB::raw("COUNT(CASE WHEN scan_masuk IS NOT NULL OR scan_pulang IS NOT NULL THEN 1 END) as total_hadir"),
            // Hitung total telat berdasarkan scan_masuk lebih dari jam 08:00
            DB::raw("COUNT(CASE WHEN TIME(scan_masuk) > '08:00:00' THEN 1 END) as total_telat"),
            // Hitung total pulang awal berdasarkan scan_pulang kurang dari jam 17:00
            DB::raw("COUNT(CASE WHEN TIME(scan_pulang) < '17:00:00' THEN 1 END) as total_awal"),
            
            DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' THEN 1 END) as total_pengecualian"),
            // Hitung leave per kategori
            DB::raw("COUNT(CASE WHEN pengecualian IN ('SAKIT', 'sakit dg srt dokter') THEN 1 END) as total_sakit"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'SAKIT TANPA SD' THEN 1 END) as total_sakit_tanpa_sd"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'CUTI MELAHIRKAN' THEN 1 END) as total_cuti_melahirkan"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'DINAS LUAR' THEN 1 END) as total_dinas_luar"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'CUTI TAHUNAN' THEN 1 END) as total_cuti_tahunan"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'CUTI' THEN 1 END) as total_cuti"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'IZIN' THEN 1 END) as total_izin"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'ANAK BTIS/SUNAT' THEN 1 END) as total_anak_btis"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'ISTRI MELAHIRKAN' THEN 1 END) as total_istri_melahirkan"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'MENIKAH' THEN 1 END) as total_menikah"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'OT/MTUA/KLG MGL' THEN 1 END) as total_ot_mtua_klg_mgl"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'WFH' THEN 1 END) as total_wfh"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 END) as total_paruh_waktu"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'LIBUR' THEN 1 END) as total_libur"),
            DB::raw("COUNT(CASE WHEN pengecualian = 'ERROR' THEN 1 END) as total_error"),

            // Hitung total hk berdasarkan kolom hk
            DB::raw("SUM(hk) as total_hk") // Menambahkan perhitungan jumlah HK

        )
        ->groupBy('nama', DB::raw('MONTH(tanggal)'), DB::raw('YEAR(tanggal)')) // Kelompokkan berdasarkan nama, bulan, dan tahun
        ->get();

    return view('presensi.report_presensi', [
        'rekapKehadiran' => $rekapKehadiran,
        'datanama' => $datanama,
    ]);
}

    public function exportExcel(Request $request)
    {
    
        $month = $request->bulanfilter;
        $year = $request->tahunfilter;
        $name = $request->namafilter;
    
        return Excel::download(new PresensiExport($month, $year, $name), 'rekap-presensi-bulanan.xlsx');
    }

    public function getPresensiDetail(Request $request)
{
    $nama = $request->input('nama');
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');
    $status = $request->input('status');

    $query = EmployeePresensi::select(
        'nama',
        'tanggal',
        'scan_masuk',
        'scan_pulang',
        'pengecualian'
    )
    ->where('nama', $nama)
    ->whereMonth('tanggal', $bulan)
    ->whereYear('tanggal', $tahun);

    if ($status == 'Hadir') {
        // Menampilkan data Hadir (scan masuk dan scan pulang tidak null)
        $query->whereNotNull('scan_masuk')
              ->whereNotNull('scan_pulang');
    } elseif ($status == 'Telat') {
        // Misalkan 'Telat' adalah kondisi di mana scan masuk lebih dari jam tertentu
        $query->whereTime('scan_masuk', '>', '08:00:00');
    } elseif ($status == 'Awal') {
        // Misalkan 'Pulang Cepat' adalah kondisi di mana scan pulang kurang dari jam tertentu
        $query->whereTime('scan_pulang', '<', '17:00:00');
    } elseif ($status == 'HK') {
        // Tambahkan kondisi untuk HK (hari kerja) jika diperlukan
        // Misalnya, pengecualian kolom "pengecualian" ada status "HK"
        $query->where('HK', 1);
    } elseif ($status == 'Leave') {
        // Menampilkan data hanya jika kolom pengecualian memiliki nilai
        $query->whereNotNull('pengecualian')
              ->where('pengecualian', '!=', ''); // Pastikan nilai tidak kosong
    }    
    elseif ($status == 'Sakit') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['SAKIT', 'sakit dg srt dokter']);
    }
    elseif ($status == 'stsd') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['SAKIT TANPA SD']);
    }
    elseif ($status == 'cuti') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['CUTI']);
    }
    elseif ($status == 'izin') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['IZIN']);
    }
    elseif ($status == 'dl') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['DINAS LUAR']);
    }
    elseif ($status == 'ct') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['CUTI TAHUNAN']);
    }
    elseif ($status == 'cm') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['CUTI MELAHIRKAN']);
    }
    elseif ($status == 'nikah') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['MENIKAH']);
    }
    elseif ($status == 'im') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['ISTRI MELAHIRKAN']);
    }
    elseif ($status == 'as') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['ANAK BTIS/SUNAT']);
    }
    elseif ($status == 'mgl') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['OT/MTUA/KLG MGL']);
    }
    elseif ($status == 'wfh') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['WFH']);
    }
    elseif ($status == 'pw') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['PARUH WAKTU']);
    }
    elseif ($status == 'libur') {
        // Mencari data dengan pengecualian bernilai 'SAKIT' atau 'sakit dg srt dokter'
        $query->whereIn('pengecualian', ['LIBUR']);
    }

    // Eksekusi query untuk mendapatkan hasil
    $presensi = $query->get();
    // Kirim data ke view atau JSON sesuai kebutuhan
    return response()->json([
        'status' => 'success',
        'presensi' => $presensi, 
        
    ]);
}


    

    public function destroy($id)
    {
        try {
            $participants = EmployeePresensi::findOrFail($id);
            $participants->delete();

            return response()->json(['msg' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $participants = EmployeePresensi::findOrFail($id);
            $participants->delete();

            return response()->json(['msg' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
