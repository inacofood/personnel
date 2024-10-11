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

    // Constructor to accept month and year
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
                DB::raw("COUNT(CASE WHEN TIME(scan_masuk) > '08:00:00' THEN 1 END) as total_telat"),
                DB::raw("COUNT(CASE WHEN TIME(scan_pulang) < '17:00:00' THEN 1 END) as total_awal"),
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

        // Apply filters for month and year
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

    // Definisikan heading kolom untuk file Excel
    public function headings(): array
    {
        return [
            'Nama', 'Bulan', 'Tahun', 'Total Hadir', 'Total Telat', 'Total Pulang Awal', 'Total Pengecualian',
            'Total Sakit', 'Total Sakit Tanpa SD', 'Total Cuti Melahirkan', 'Total Dinas Luar', 'Total Cuti Tahunan',
            'Total Cuti', 'Total Izin', 'Total Anak BTIS/Sunat', 'Total Istri Melahirkan', 'Total Menikah',
            'Total OT/MTUA/KLG MGL', 'Total WFH', 'Total Paruh Waktu', 'Total Libur', 'Total Error', 'Total HK'
        ];
    }

    // Mappings data untuk setiap baris
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
