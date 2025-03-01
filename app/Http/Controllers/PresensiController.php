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
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));

        $presensi = EmployeePresensi::whereMonth('tanggal', $bulan)
                                     ->whereYear('tanggal', $tahun)
                                     ->get();

        return view('presensi.index', [
            'presensi' => $presensi,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    public function import(Request $request)
{
    set_time_limit(300);

    $file = $request->file('file');
    $filePath = $file->getRealPath();
    $spreadsheet = IOFactory::load($filePath);

    try {
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheet->removeRow(1, 1);
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getFormattedValue();
                }

                $tanggal = null;
                $joinDate = null;

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

                $scan_masuk = isset($rowData[5]) && preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $rowData[5]) ? $rowData[5] . ':00' : null;
                $scan_pulang = isset($rowData[6]) && preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $rowData[6]) ? $rowData[6] . ':00' : null;

                $data = [
                    'nik' => $nik,
                    'nama' => $rowData[1] ?? null,
                    'tanggal' => $tanggal,
                    'jam_kerja' => $rowData[3] ?? null,
                    'week' => $rowData[4] ?? null,
                    'scan_masuk' => $scan_masuk,
                    'scan_pulang' => $scan_pulang,
                    'terlambat' => $rowData[7] ? $rowData[7] . ':00' : null,
                    'pulang_cepat' => $rowData[8] ? $rowData[8] . ':00' : null,
                    'absent' => $rowData[9] ?? null,
                    'pengecualian' => $rowData[10] ?? null,
                    'HK' => !empty($rowData[11]) && is_numeric($rowData[11]) ? $rowData[11] : null,
                    'dept' => $rowData[12] ?? null,
                    'section' => $rowData[13] ?? null,
                    'grade' => $rowData[14] ?? null,
                    'join_date' => $joinDate,
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
        ->select('nama', 'nik', 'grade','dept')
        ->distinct()
        ->get();
    $datadept = DB::table('employee_presensi_bulanan')
        ->select('dept')
        ->distinct()
        ->get();

    $rekapKehadiran = DB::table('employee_presensi_bulanan')
        ->select(
            'nama',
            'nik',
            'dept',
            'grade',
            DB::raw('GROUP_CONCAT(DISTINCT jam_kerja SEPARATOR ", ") as jam_kerja'),
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw("COUNT(CASE WHEN scan_masuk IS NOT NULL OR scan_pulang IS NOT NULL THEN 1 END) as total_hadir"),
            DB::raw("
                COUNT(
                    CASE
                        WHEN absent = 'TRUE'
                        AND jam_kerja != 'Fleksibel'
                        AND pengecualian != 'LIBUR'
                        AND (
                            (jam_kerja = 'Crew Marketing' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                            (jam_kerja = 'Driver Ekspedisi S-J' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                            (jam_kerja = 'Driver Ekspedisi Sab' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                            (jam_kerja = 'Driver Ops' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                            (jam_kerja = 'Shift 1A 5 jam' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                            (jam_kerja = 'Shift 1C' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                            (jam_kerja = 'Staff Up 5 HK' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6)
                        )
                        THEN 1
                    END
                ) as total_absent
            "),
            DB::raw("
            COUNT(
                CASE
                    WHEN TIME(scan_masuk) > TIME(CASE jam_kerja
                        WHEN 'Shift 1A' THEN '06:00:00'
                        WHEN 'Shift 1B' THEN '07:00:00'
                        WHEN 'Shift 1C' THEN '08:00:00'
                        WHEN 'Shift 1D' THEN '09:00:00'
                        WHEN 'Shift 1E' THEN '10:00:00'
                        WHEN 'Shift 1F' THEN '05:00:00'
                        WHEN 'Shift 2A' THEN '11:00:00'
                        WHEN 'Shift 2B' THEN '12:00:00'
                        WHEN 'Shift 2C' THEN '13:00:00'
                        WHEN 'Shift 2D' THEN '14:00:00'
                        WHEN 'Shift 2E' THEN '15:00:00'
                        WHEN 'Shift 2F' THEN '16:00:00'
                        WHEN 'Shift 3A' THEN '22:00:00'
                        WHEN 'Shift 3B' THEN '23:00:00'
                        WHEN 'Shift 1 5 jam' THEN '07:00:00'
                        WHEN 'Shift 1A 5 jam' THEN '08:00:00'
                        WHEN 'Shift 1B 5 jam' THEN '06:00:00'
                        WHEN 'Shift 1C 5 jam' THEN '10:00:00'
                        WHEN 'Shift 3 5 jam' THEN '23:00:00'
                        WHEN 'Shift 3A 5 jam' THEN '24:00:00'
                        WHEN 'Shift 2 5 jam' THEN '12:00:00'
                        WHEN 'Shift 2A 5 jam' THEN '17:00:00'
                        WHEN 'Shift 2B 5 jam' THEN '18:00:00'
                        WHEN 'Staff Up 5 HK' THEN '08:00:00'
                        WHEN 'Driver Ops' THEN '08:00:00'
                        WHEN 'Driver Ekspedisi S-J' THEN '08:00:00'
                        WHEN 'Driver Ekspedisi Sab' THEN '08:00:00'
                        WHEN 'Fleksibel' THEN '07:00:00'
                        WHEN 'Laundry Sab' THEN '06:00:00'
                        WHEN 'OB Sab' THEN '07:00:00'
                        WHEN 'OB Sen-Jum' THEN '06:30:00'
                        WHEN 'Crew Marketing' THEN '08:00:00'
                                END)
                                AND (
                                    (jam_kerja = 'Crew Marketing' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                    (jam_kerja = 'Driver Ekspedisi S-J' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                    (jam_kerja = 'Driver Ekspedisi Sab' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                    (jam_kerja = 'Driver Ops' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                    (jam_kerja = 'Shift 1A 5 jam' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                    (jam_kerja = 'Shift 1C' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                    (jam_kerja = 'Staff Up 5 HK' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6)
                                )
                                THEN 1
                            END
                        ) as total_telat
                    "),
                    DB::raw("
                    COUNT(
                        CASE
                            WHEN TIME(scan_pulang) < TIME(CASE jam_kerja
                                WHEN 'Shift 1A' THEN '14:00:00'
                                WHEN 'Shift 1B' THEN '15:00:00'
                                WHEN 'Shift 1C' THEN '16:00:00'
                                WHEN 'Shift 1D' THEN '17:00:00'
                                WHEN 'Shift 1E' THEN '18:00:00'
                                WHEN 'Shift 1F' THEN '13:00:00'
                                WHEN 'Shift 2A' THEN '19:00:00'
                                WHEN 'Shift 2B' THEN '20:00:00'
                                WHEN 'Shift 2C' THEN '21:00:00'
                                WHEN 'Shift 2D' THEN '22:00:00'
                                WHEN 'Shift 2E' THEN '23:00:00'
                                WHEN 'Shift 2F' THEN '24:00:00'
                                WHEN 'Shift 3A' THEN '06:00:00'
                                WHEN 'Shift 3B' THEN '07:00:00'
                                WHEN 'Shift 1 5 jam' THEN '12:00:00'
                                WHEN 'Shift 1A 5 jam' THEN '13:10:00'
                                WHEN 'Shift 1B 5 jam' THEN '11:00:00'
                                WHEN 'Shift 1C 5 jam' THEN '15:00:00'
                                WHEN 'Shift 3 5 jam' THEN '04:00:00'
                                WHEN 'Shift 3A 5 jam' THEN '05:00:00'
                                WHEN 'Shift 2 5 jam' THEN '17:00:00'
                                WHEN 'Shift 2A 5 jam' THEN '22:00:00'
                                WHEN 'Shift 2B 5 jam' THEN '23:00:00'
                                WHEN 'Staff Up 5 HK' THEN '17:00:00'
                                WHEN 'Driver Ops' THEN '17:00:00'
                                WHEN 'Driver Ekspedisi S-J' THEN '16:00:00'
                                WHEN 'Driver Ekspedisi Sab' THEN '13:10:00'
                                WHEN 'Fleksibel' THEN '23:59:00'
                                WHEN 'Laundry Sab' THEN '11:10:00'
                                WHEN 'OB Sab' THEN '13:10:00'
                                WHEN 'OB Sen-Jum' THEN '16:30:00'
                                WHEN 'Crew Marketing' THEN '17:00:00'
                            END)
                            AND (
                                (jam_kerja = 'Crew Marketing' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                (jam_kerja = 'Driver Ekspedisi S-J' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                (jam_kerja = 'Driver Ekspedisi Sab' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                (jam_kerja = 'Driver Ops' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6) OR
                                (jam_kerja = 'Shift 1A 5 jam' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                (jam_kerja = 'Shift 1C' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7) OR
                                (jam_kerja = 'Staff Up 5 HK' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6)
                            )
                            THEN 1
                                END
                                ) as total_awal
            "),
            DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' AND pengecualian != 'DINAS LUAR' AND pengecualian != 'LIBUR' THEN 1 END) as total_pengecualian"),
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
            DB::raw("SUM(hk) as total_hk")
        )
        ->groupBy('nama','nik','dept', 'grade', DB::raw('MONTH(tanggal)'), DB::raw('YEAR(tanggal)'))
        ->get();

    return view('presensi.report_presensi', [
        'rekapKehadiran' => $rekapKehadiran,
        'datanama' => $datanama,
        'datadept'=> $datadept,
    ]);
}

public function getPresensiDetail(Request $request)
{
        $request->validate([
        'nama' => 'required|string',
        'bulan' => 'required|integer',
        'tahun' => 'required|integer',
        'status' => 'required|string',
    ]);

    $nama = $request->input('nama');
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');
    $status = $request->input('status');

    $query = EmployeePresensi::select(
        'nama',
        'jam_kerja',
        'tanggal',
        'scan_masuk',
        'scan_pulang',
        'absent',
        'pengecualian'
    )
    ->where('nama', $nama)
    ->whereMonth('tanggal', $bulan)
    ->whereYear('tanggal', $tahun);

    if ($status == 'Hadir') {
        $query->where(function($query) {
            $query->whereNotNull('scan_masuk')
                  ->orWhereNotNull('scan_pulang');
        });
    } elseif ($status == 'Telat') {
        $shifts = [
            'Shift 1A' => ['start' => '06:00:00', 'end' => '14:00:00'],
            'Shift 1B' => ['start' => '07:00:00', 'end' => '15:00:00'],
            'Shift 1C' => ['start' => '08:00:00', 'end' => '16:00:00'],
            'Shift 1D' => ['start' => '09:00:00', 'end' => '17:00:00'],
            'Shift 1E' => ['start' => '10:00:00', 'end' => '18:00:00'],
            'Shift 1F' => ['start' => '05:00:00', 'end' => '13:00:00'],
            'Shift 2A' => ['start' => '11:00:00', 'end' => '19:00:00'],
            'Shift 2B' => ['start' => '12:00:00', 'end' => '20:00:00'],
            'Shift 2C' => ['start' => '13:00:00', 'end' => '21:00:00'],
            'Shift 2D' => ['start' => '14:00:00', 'end' => '22:00:00'],
            'Shift 2E' => ['start' => '15:00:00', 'end' => '23:00:00'],
            'Shift 2F' => ['start' => '16:00:00', 'end' => '24:00:00'],
            'Shift 3A' => ['start' => '22:00:00', 'end' => '06:00:00'],
            'Shift 3B' => ['start' => '23:00:00', 'end' => '07:00:00'],
            'Shift 1 5 jam' => ['start' => '07:00:00', 'end' => '12:00:00'],
            'Shift 1A 5 jam' => ['start' => '08:00:00', 'end' => '13:10:00'],
            'Shift 1B 5 jam' => ['start' => '06:00:00', 'end' => '11:00:00'],
            'Shift 1C 5 jam' => ['start' => '10:00:00', 'end' => '15:00:00'],
            'Shift 3 5 jam' => ['start' => '23:00:00', 'end' => '04:00:00'],
            'Shift 3A 5 jam' => ['start' => '24:00:00', 'end' => '05:00:00'],
            'Shift 2 5 jam' => ['start' => '12:00:00', 'end' => '17:00:00'],
            'Shift 2A 5 jam' => ['start' => '17:00:00', 'end' => '22:00:00'],
            'Shift 2B 5 jam' => ['start' => '18:00:00', 'end' => '23:00:00'],
            'Staff Up 5 HK' => ['start' => '08:00:00', 'end' => '17:00:00'],
            'Driver Ops' => ['start' => '08:00:00', 'end' => '17:00:00'],
            'Driver Ekspedisi S-J' => ['start' => '08:00:00', 'end' => '16:00:00'],
            'Driver Ekspedisi Sab' => ['start' => '08:00:00', 'end' => '13:10:00'],
            'Fleksibel' => ['start' => '07:00:00', 'end' => '23:59:00'],
            'Laundry Sab' => ['start' => '06:00:00', 'end' => '11:10:00'],
            'OB Sab' => ['start' => '07:00:00', 'end' => '13:10:00'],
            'OB Sen-Jum' => ['start' => '06:30:00', 'end' => '16:30:00'],
            'Crew Marketing' => ['start' => '08:00:00', 'end' => '17:00:00'],
        ];

        $query->where(function ($q) use ($shifts) {
            foreach ($shifts as $shiftName => $shiftTimes) {
                $q->orWhere(function ($query) use ($shiftName, $shiftTimes) {
                    $query->where('jam_kerja', $shiftName)
                          ->whereTime('scan_masuk', '>', $shiftTimes['start'])
                          ->where(function ($query) {
                              $query->where(function ($q) {
                                  $q->where('jam_kerja', 'Crew Marketing')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Driver Ekspedisi S-J')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Driver Ekspedisi Sab')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Driver Ops')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Shift 1A 5 jam')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Shift 1C')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
                              })->orWhere(function ($q) {
                                  $q->where('jam_kerja', 'Staff Up 5 HK')
                                    ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
                              });
                          });
                });
            }
        });


    } elseif ($status == 'Awal') {
        $shifts = [
            'Shift 1A' => ['start' => '06:00:00', 'end' => '14:00:00'],
            'Shift 1B' => ['start' => '07:00:00', 'end' => '15:00:00'],
            'Shift 1C' => ['start' => '08:00:00', 'end' => '16:00:00'],
            'Shift 1D' => ['start' => '09:00:00', 'end' => '17:00:00'],
            'Shift 1E' => ['start' => '10:00:00', 'end' => '18:00:00'],
            'Shift 1F' => ['start' => '05:00:00', 'end' => '13:00:00'],
            'Shift 2A' => ['start' => '11:00:00', 'end' => '19:00:00'],
            'Shift 2B' => ['start' => '12:00:00', 'end' => '20:00:00'],
            'Shift 2C' => ['start' => '13:00:00', 'end' => '21:00:00'],
            'Shift 2D' => ['start' => '14:00:00', 'end' => '22:00:00'],
            'Shift 2E' => ['start' => '15:00:00', 'end' => '23:00:00'],
            'Shift 2F' => ['start' => '16:00:00', 'end' => '24:00:00'],
            'Shift 3A' => ['start' => '22:00:00', 'end' => '06:00:00'],
            'Shift 3B' => ['start' => '23:00:00', 'end' => '07:00:00'],
            'Shift 1 5 jam' => ['start' => '07:00:00', 'end' => '12:00:00'],
            'Shift 1A 5 jam' => ['start' => '08:00:00', 'end' => '13:10:00'],
            'Shift 1B 5 jam' => ['start' => '06:00:00', 'end' => '11:00:00'],
            'Shift 1C 5 jam' => ['start' => '10:00:00', 'end' => '15:00:00'],
            'Shift 3 5 jam' => ['start' => '23:00:00', 'end' => '04:00:00'],
            'Shift 3A 5 jam' => ['start' => '24:00:00', 'end' => '05:00:00'],
            'Shift 2 5 jam' => ['start' => '12:00:00', 'end' => '17:00:00'],
            'Shift 2A 5 jam' => ['start' => '17:00:00', 'end' => '22:00:00'],
            'Shift 2B 5 jam' => ['start' => '18:00:00', 'end' => '23:00:00'],
            'Staff Up 5 HK' => ['start' => '08:00:00', 'end' => '17:00:00'],
            'Driver Ops' => ['start' => '08:00:00', 'end' => '17:00:00'],
            'Driver Ekspedisi S-J' => ['start' => '08:00:00', 'end' => '16:00:00'],
            'Driver Ekspedisi Sab' => ['start' => '08:00:00', 'end' => '13:10:00'],
            'Fleksibel' => ['start' => '07:00:00', 'end' => '23:59:00'],
            'Laundry Sab' => ['start' => '06:00:00', 'end' => '11:10:00'],
            'OB Sab' => ['start' => '07:00:00', 'end' => '13:10:00'],
            'OB Sen-Jum' => ['start' => '06:30:00', 'end' => '16:30:00'],
            'Crew Marketing' => ['start' => '08:00:00', 'end' => '17:00:00'],
        ];

        $query->where(function ($q) use ($shifts) {
            foreach ($shifts as $shiftName => $shiftTimes) {
                $q->orWhere(function ($query) use ($shiftName, $shiftTimes) {
                    $query->where('jam_kerja', $shiftName)
                          ->whereTime('scan_pulang', '<', $shiftTimes['end']);
                });
            }
        })
        ->where(function ($q) {
            $q->where(function ($q) {
                $q->where('jam_kerja', 'Crew Marketing')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Driver Ekspedisi S-J')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Driver Ekspedisi Sab')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Driver Ops')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Shift 1A 5 jam')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Shift 1C')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 7');
            })
            ->orWhere(function ($q) {
                $q->where('jam_kerja', 'Staff Up 5 HK')->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6');
            });
        });

    } elseif ($status == 'HK') {
        $query->where('HK', 1);
    } elseif ($status == 'absent') {
        $query->where('absent', 'True')
        ->where(function ($subQuery) {
            $subQuery->where(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Crew Marketing')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Driver Ekspedisi S-J')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Driver Ekspedisi Sab')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Driver Ops')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Fleksibel')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Shift 1A 5 jam')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Shift 1C')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
            })->orWhere(function ($innerQuery) {
                $innerQuery->where('jam_kerja', 'Staff Up 5 HK')
                           ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
            });
        });

    } elseif ($status == 'Leave') {
        $query->whereNotNull('pengecualian')
              ->where('pengecualian', '!=', '')
              ->whereNotIn('pengecualian', ['DINAS LUAR', 'LIBUR']);
    }
    elseif ($status == 'Sakit') {
        $query->whereIn('pengecualian', ['SAKIT', 'sakit dg srt dokter']);
    }
    elseif ($status == 'stsd') {
        $query->whereIn('pengecualian', ['SAKIT TANPA SD']);
    }
    elseif ($status == 'cuti') {
        $query->whereIn('pengecualian', ['CUTI']);
    }
    elseif ($status == 'izin') {
        $query->whereIn('pengecualian', ['IZIN']);
    }
    elseif ($status == 'dl') {
        $query->whereIn('pengecualian', ['DINAS LUAR']);
    }
    elseif ($status == 'ct') {
        $query->whereIn('pengecualian', ['CUTI TAHUNAN']);
    }
    elseif ($status == 'cm') {
        $query->whereIn('pengecualian', ['CUTI MELAHIRKAN']);
    }
    elseif ($status == 'nikah') {
        $query->whereIn('pengecualian', ['MENIKAH']);
    }
    elseif ($status == 'im') {
        $query->whereIn('pengecualian', ['ISTRI MELAHIRKAN']);
    }
    elseif ($status == 'as') {
        $query->whereIn('pengecualian', ['ANAK BTIS/SUNAT']);
    }
    elseif ($status == 'mgl') {
        $query->whereIn('pengecualian', ['OT/MTUA/KLG MGL']);
    }
    elseif ($status == 'wfh') {
        $query->whereIn('pengecualian', ['WFH']);
    }
    elseif ($status == 'pw') {
        $query->whereIn('pengecualian', ['PARUH WAKTU']);
    }
    elseif ($status == 'libur') {
        $query->whereIn('pengecualian', ['LIBUR']);
    }

    $presensi = $query->get();
    return response()->json([
        'status' => 'success',
        'presensi' => $presensi,

    ]);
}

    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $name = $request->namafilter;
        $dept = $request->dept;

        return Excel::download(new PresensiExport($startDate, $endDate, $name, $dept), 'rekap-presensi.xlsx');
    }

    public function deletepresensi($id)
    {

        $data = EmployeePresensi::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Presensi berhasil dihapus!');
    }

    public function editpresensi($id_presensi_bulanan)
{
    $presensi = EmployeePresensi::findOrFail($id_presensi_bulanan);
    return view('presensi.edit', compact('presensi'));
}

public function updatepresensi(Request $request, $id_presensi_bulanan)
{
    $data = [
        'nik' => $request->nik !== null && $request->nik !== '-' ? $request->nik : null,
        'nama' => $request->nama !== null && $request->nama !== '-' ? $request->nama : null,
        'tanggal' => $request->tanggal !== null && $request->tanggal !== '-' ? $request->tanggal : null,
        'week' => $request->week !== null && $request->week !== '-' ? $request->week : null,
        'jam_kerja' => $request->jam_kerja !== null && $request->jam_kerja !== '-' ? $request->jam_kerja : null,
        'scan_masuk' => $request->scan_masuk !== null && $request->scan_masuk !== '-' ? $request->scan_masuk : null,
        'scan_pulang' => $request->scan_pulang !== null && $request->scan_pulang !== '-' ? $request->scan_pulang : null,
        'terlambat' => $request->terlambat !== null && $request->terlambat !== '-' ? $request->terlambat : null,
        'pulang_cepat' => $request->pulang_cepat !== null && $request->pulang_cepat !== '-' ? $request->pulang_cepat : null,
        'absent' => $request->absent !== null && $request->absent !== '-' ? $request->absent : null,
        'pengecualian' => $request->pengecualian !== null && $request->pengecualian !== '-' ? $request->pengecualian : null,
        'HK' => $request->HK !== null && $request->HK !== '-' ? $request->HK : null,
        'dept' => $request->dept !== null && $request->dept !== '-' ? $request->dept : null,
        'section' => $request->section !== null && $request->section !== '-' ? $request->section : null,
        'grade' => $request->grade !== null && $request->grade !== '-' ? $request->grade : null,
        'join_date' => $request->join_date !== null && $request->join_date !== '-' ? $request->join_date : null,
        'jt' => $request->jt !== null && $request->jt !== '-' ? $request->jt : null,
        'atasan' => $request->atasan !== null && $request->atasan !== '-' ? $request->atasan : null,
        'updated_by' => auth()->id(),
    ];

    // Update database
    DB::table('employee_presensi_bulanan')
        ->where('id_presensi_bulanan', $id_presensi_bulanan)
        ->update($data);

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diupdate!');
}



}
