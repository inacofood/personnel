<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class PresensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $month;
    protected $year;
    protected $name;

    public function __construct($month = null, $year = null,$name = null)
    {
        $this->month = $month;
        $this->year = $year;
        $this->name = $name;
    }
    // Ambil data dari fungsi yang sama seperti di view
    public function collection()
    {
      
        $query = DB::table('employee_presensi_bulanan')
            ->select(
                'nama', 
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw("COUNT(CASE WHEN scan_masuk IS NOT NULL OR scan_pulang IS NOT NULL THEN 1 END) as total_hadir"),
                DB::raw("COUNT(CASE WHEN TIME(scan_masuk) > TIME(CASE jam_kerja 
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
                 END) THEN 1 END) as total_telat"),
            DB::raw("COUNT(CASE WHEN TIME(scan_pulang) < TIME(CASE jam_kerja 
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
                 END) THEN 1 END) as total_awal"),
                DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' THEN 1 END) as total_pengecualian"),
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
            );

        if ($this->month) {
            $query->whereMonth('tanggal', $this->month);
        }
        if ($this->year) {
            $query->whereYear('tanggal', $this->year);
        }
        if ($this->name) {
            $query->where('nama', $this->name);
        }

        return $query
            ->groupBy('nama', DB::raw('MONTH(tanggal)'), DB::raw('YEAR(tanggal)'))
            ->get();
    

        return $query->groupBy('nama', DB::raw('MONTH(tanggal)'), DB::raw('YEAR(tanggal)'))->get();
    }

    public function headings(): array
    {
        return [
            'Nama', 'Bulan', 'Tahun', 'Total Hadir', 'Total Telat', 'Total Pulang Awal', 'Total Pengecualian',
            'Total Sakit', 'Total Sakit Tanpa SD', 'Total Cuti Melahirkan', 'Total Dinas Luar', 'Total Cuti Tahunan',
            'Total Cuti', 'Total Izin', 'Total Anak BTIS/Sunat', 'Total Istri Melahirkan', 'Total Menikah',
            'Total OT/MTUA/KLG MGL', 'Total WFH', 'Total Paruh Waktu', 'Total Libur', 'Total Error', 'Total HK'
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama,
            \Carbon\Carbon::create()->month($row->bulan)->translatedFormat('F'), // Konversi angka bulan ke nama bulan
            $row->tahun,
            $row->total_hadir,
            $row->total_telat,
            $row->total_awal,
            $row->total_pengecualian,
            $row->total_sakit,
            $row->total_sakit_tanpa_sd,
            $row->total_cuti_melahirkan,
            $row->total_dinas_luar,
            $row->total_cuti_tahunan,
            $row->total_cuti,
            $row->total_izin,
            $row->total_anak_btis,
            $row->total_istri_melahirkan,
            $row->total_menikah,
            $row->total_ot_mtua_klg_mgl,
            $row->total_wfh,
            $row->total_paruh_waktu,
            $row->total_libur,
            $row->total_error,
            $row->total_hk
        ];
    }
}
