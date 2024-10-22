<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersRole;

class DashboardController extends Controller
{

    public function index(Request $request)
{
    // dd($request); // Debugging optional

    $user = Auth::user();
    $roles = UsersRole::where('id_users', $user->id)->pluck('id_role');

    // Get the month and year from the request
    $bulan = $request->input('bulan');
    $tahun = $request->input('tahun');

    // Check if the year is provided, if not, use the current month and year
    if (!$tahun) {
        $bulan = date('m'); // Set current month if not provided
        $tahun = date('Y'); // Set current year if not provided
    }

    // Call your existing data retrieval methods and filter as needed
    $data = $this->rekapPresensiBulanan($bulan, $tahun);
    $totals = $this->getTotalPresensi($bulan, $tahun);
    $telatAwalData = $this->rekapTelatAwalBulanan($bulan, $tahun);
    $rekapKehadiran = $this->rekapTelatBulanan($bulan, $tahun);
    $rekapLeave = $this->rekapLeaveTerbanyak($bulan, $tahun);

    return view('dashboard.index', compact('roles', 'data', 'totals', 'telatAwalData', 'rekapKehadiran', 'rekapLeave'));
}

    public function rekapPresensiBulanan($bulan, $tahun)
    {
        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
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
                DB::raw("SUM(hk) as total_hk")
            )
            ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan yang diberikan
            ->whereYear('tanggal', $tahun)  // Filter berdasarkan tahun yang diberikan
            ->get();
    
        $totalPengecualian = $rekapKehadiran->sum('total_pengecualian') ?: 1; 
    
        $data = [
            ['name' => 'Sakit', 'y' => ($rekapKehadiran->sum('total_sakit') / $totalPengecualian) * 100],
            ['name' => 'Sakit Tanpa SD', 'y' => ($rekapKehadiran->sum('total_sakit_tanpa_sd') / $totalPengecualian) * 100],
            ['name' => 'Cuti Melahirkan', 'y' => ($rekapKehadiran->sum('total_cuti_melahirkan') / $totalPengecualian) * 100],
            ['name' => 'Dinas Luar', 'y' => ($rekapKehadiran->sum('total_dinas_luar') / $totalPengecualian) * 100],
            ['name' => 'Cuti Tahunan', 'y' => ($rekapKehadiran->sum('total_cuti_tahunan') / $totalPengecualian) * 100],
            ['name' => 'Cuti', 'y' => ($rekapKehadiran->sum('total_cuti') / $totalPengecualian) * 100],
            ['name' => 'Izin', 'y' => ($rekapKehadiran->sum('total_izin') / $totalPengecualian) * 100],
            ['name' => 'Anak BTIS/Sunat', 'y' => ($rekapKehadiran->sum('total_anak_btis') / $totalPengecualian) * 100],
            ['name' => 'Istri Melahirkan', 'y' => ($rekapKehadiran->sum('total_istri_melahirkan') / $totalPengecualian) * 100],
            ['name' => 'Menikah', 'y' => ($rekapKehadiran->sum('total_menikah') / $totalPengecualian) * 100],
            ['name' => 'OT/MTUA/KLG MGL', 'y' => ($rekapKehadiran->sum('total_ot_mtua_klg_mgl') / $totalPengecualian) * 100],
            ['name' => 'WFH', 'y' => ($rekapKehadiran->sum('total_wfh') / $totalPengecualian) * 100],
            ['name' => 'Paruh Waktu', 'y' => ($rekapKehadiran->sum('total_paruh_waktu') / $totalPengecualian) * 100],
        ];
        return $data;
    }

    public function rekapLeaveTerbanyak($bulan, $tahun)
    {
        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
                'nama',
                'pengecualian', // Kategori Leave dari pengecualian
                DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' AND pengecualian != 'DINAS LUAR' THEN 1 END) as total_leave")
            )
            ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan yang diberikan
            ->whereYear('tanggal', $tahun)  // Filter berdasarkan tahun yang diberikan
            ->groupBy('nama', 'pengecualian') // Tambahkan pengecualian sebagai grup
            ->orderBy('total_leave', 'DESC') // Sort by total leave in descending order
            ->limit(5) // Limit to the top 5 employees
            ->get();
    
        $data = [];
        foreach ($rekapKehadiran as $kehadiran) {
            $data[] = [
                'nama' => $kehadiran->nama, 
                'kategori_leave' => $kehadiran->pengecualian, // Kategori leave akan berasal dari pengecualian
                'total_leave' => $kehadiran->total_leave
            ];
        }
        return $data;
    }    


    public function rekapTelatAwalBulanan($bulan, $tahun)
    {
        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
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
                END) THEN 1 END) as total_awal")
            )
            ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan yang diberikan
            ->whereYear('tanggal', $tahun)  // Filter berdasarkan tahun yang diberikan
            ->first(); 

        $data = [
            ['name' => 'Total Telat', 'y' => $rekapKehadiran->total_telat ?? 0],
            ['name' => 'Total Pulang Awal', 'y' => $rekapKehadiran->total_awal ?? 0]
        ];
    
        return $data;
    }


    public function rekapTelatBulanan($bulan, $tahun)
{
    $rekapKehadiran = DB::table('employee_presensi_bulanan')
        ->select(
            'nama', // assuming there's an 'employee_id' or similar identifier for each employee
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
            END) THEN 1 END) as total_telat")
        )
        ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan yang diberikan
        ->whereYear('tanggal', $tahun)  // Filter berdasarkan tahun yang diberikan
        ->groupBy('nama')
        ->orderBy('total_telat', 'DESC')
        ->limit(5)
        ->get();

    $data = [];
    foreach ($rekapKehadiran as $kehadiran) {
        $data[] = [
            'nama' => $kehadiran->nama, 
            'total_telat' => $kehadiran->total_telat
        ];
    }
    return $data;
}

    
public function getTotalPresensi($bulan, $tahun)
{
    $totals = DB::table('employee_presensi_bulanan')
        ->select(
            DB::raw("SUM(CASE WHEN pengecualian IN ('SAKIT', 'sakit dg srt dokter') THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN pengecualian = 'SAKIT TANPA SD' THEN 1 ELSE 0 END) as total_sakit_tanpa_sd"),
            DB::raw("SUM(CASE WHEN pengecualian = 'CUTI MELAHIRKAN' THEN 1 ELSE 0 END) as total_cuti_melahirkan"),
            DB::raw("SUM(CASE WHEN pengecualian = 'DINAS LUAR' THEN 1 ELSE 0 END) as total_dinas_luar"),
            DB::raw("SUM(CASE WHEN pengecualian = 'CUTI TAHUNAN' THEN 1 ELSE 0 END) as total_cuti_tahunan"),
            DB::raw("SUM(CASE WHEN pengecualian = 'CUTI' THEN 1 ELSE 0 END) as total_cuti"),
            DB::raw("SUM(CASE WHEN pengecualian = 'IZIN' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN pengecualian = 'ANAK BTIS/SUNAT' THEN 1 ELSE 0 END) as total_anak_btis"),
            DB::raw("SUM(CASE WHEN pengecualian = 'ISTRI MELAHIRKAN' THEN 1 ELSE 0 END) as total_istri_melahirkan"),
            DB::raw("SUM(CASE WHEN pengecualian = 'MENIKAH' THEN 1 ELSE 0 END) as total_menikah"),
            DB::raw("SUM(CASE WHEN pengecualian = 'OT/MTUA/KLG MGL' THEN 1 ELSE 0 END) as total_ot_mtua_klg_mgl"),
            DB::raw("SUM(CASE WHEN pengecualian = 'WFH' THEN 1 ELSE 0 END) as total_wfh"),
            DB::raw("SUM(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 ELSE 0 END) as total_paruh_waktu")
        )
        ->whereMonth('tanggal', $bulan) // Filter berdasarkan bulan yang diberikan
        ->whereYear('tanggal', $tahun)  // Filter berdasarkan tahun yang diberikan
        ->first();

    return $totals;
}


}
