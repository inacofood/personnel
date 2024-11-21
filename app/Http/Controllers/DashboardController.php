<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersRole;
use App\Models\EmployeePresensi;
use Carbon\Carbon;

class DashboardController extends Controller
{

        public function index(Request $request){

        $user = Auth::user();
        $roles = UsersRole::where('id_users', $user->id)->pluck('id_role');
       
        $bulan = $request->input('bulan', session('bulan'));
        $tahun = $request->input('tahun', session('tahun'));

        session(['bulan' => $bulan, 'tahun' => $tahun]);

        if (!$tahun) {
            $bulan = date('m');
            $tahun = date('Y');
        }
      
        $data = $this->rekapPresensiBulanan($bulan, $tahun);
        $totals = $this->getTotalLeave($bulan, $tahun);
        $telatAwalData = $this->rekapTelatAwalBulanan($bulan, $tahun);
        $rekapKehadiran = $this->rekapTelatBulanan($bulan, $tahun);
        $rekapLeave = $this->rekapLeaveTerbanyak($bulan, $tahun);


        // FUNGSI UNTUK MENGHITUNG JUMLAH LATE PERMINGGU
        $telatmingguan = DB::table('employee_presensi_bulanan')
        ->select(
            'nama',
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
        ->whereBetween('tanggal', [Carbon::now()->subDays(6), Carbon::now()])
        ->groupBy('nama')
        ->orderBy('total_telat', 'DESC')
        ->limit(5)
        ->get();

        
        // FUNGSI UNTUK MENGHITUNG JUMLAH LEAVE PERMINGGU
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();    

        $leavemingguan = DB::table('employee_presensi_bulanan')
        ->select(
            'nama',
            'pengecualian', 
            DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' AND pengecualian != 'DINAS LUAR' THEN 1 END) as total_leave")
        )
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->groupBy('nama', 'pengecualian') 
        ->orderBy('total_leave', 'DESC') 
        ->limit(5) 
        ->get();
    
       
        return view('dashboard.index', compact('roles', 'data', 'totals', 'telatAwalData', 'rekapKehadiran', 'rekapLeave', 'telatmingguan', 'leavemingguan'));
    }

    public function rekapPresensiBulanan($bulan, $tahun)
    {
     
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);
    
        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
                DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' THEN 1 END) as total_pengecualian"),
                DB::raw("COUNT(CASE WHEN pengecualian IN ('SAKIT', 'sakit dg srt dokter') THEN 1 END) as total_sakit"),
                DB::raw("COUNT(CASE WHEN pengecualian = 'SAKIT TANPA SD' THEN 1 END) as total_sakit_tanpa_sd"),
                DB::raw("COUNT(CASE WHEN pengecualian = 'CUTI MELAHIRKAN' THEN 1 END) as total_cuti_melahirkan"),
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
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
            ->get();
    
        $totalPengecualian = $rekapKehadiran->sum('total_pengecualian') ?: 1; 
    
        $data = [
            ['name' => 'Sakit', 'y' => ($rekapKehadiran->sum('total_sakit') / $totalPengecualian) * 100],
            ['name' => 'Sakit Tanpa SD', 'y' => ($rekapKehadiran->sum('total_sakit_tanpa_sd') / $totalPengecualian) * 100],
            ['name' => 'Cuti Melahirkan', 'y' => ($rekapKehadiran->sum('total_cuti_melahirkan') / $totalPengecualian) * 100],
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

    // FUNGSI MENGHITUNG 5 LEAVE TERBANYAK
    public function rekapLeaveTerbanyak($bulan, $tahun)
    {
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);

        $rekapKehadiran = DB::table('employee_presensi_bulanan')
        ->select(
            'nama',
            'pengecualian', 
            DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' AND pengecualian != 'DINAS LUAR' THEN 1 END) as total_leave")
        )
        ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
        ->groupBy('nama', 'pengecualian') 
        ->orderBy('total_leave', 'DESC') 
        ->limit(5) 
        ->get();
    
        $data = [];
        foreach ($rekapKehadiran as $kehadiran) {
            $data[] = [
                'nama' => $kehadiran->nama, 
                'kategori_leave' => $kehadiran->pengecualian, 
                'total_leave' => $kehadiran->total_leave
            ];
        }
        return $data;
    }
     

    // FUNGSI UNTUK MENAMPILKAN DATA 5 LEAVE TERBANYAK
    public function leaveterbanyak(Request $request)
    {
     
    $month = $request->input('bulan', now()->month);
    $year = $request->input('tahun', now()->year);

    $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
    $endDate = now()->setYear($year)->setMonth($month)->day(25);

    $nama = $request->input('nama'); 
    $kategoriLeave = $request->input('kategori_leave'); 

    $query = EmployeePresensi::select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
    ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $year);
    
    if ($nama) {
        $query->where('nama', $nama);
    }  

    if ($kategoriLeave) {
        $query->where('pengecualian', $kategoriLeave);
    }

    $presensi = $query->get();
    
    return view('dashboard.limaterbanyakleave', [
        'presensi' => $presensi
    ]);
}

    // FUNGSI UNTUK MENAMPILKAN JUMLAH TELAT DAN PULANG AWAL SETIAP BULAN
    public function rekapTelatAwalBulanan($bulan, $tahun)
    {
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);
    
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
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $tahun)
            ->where(function ($query) {
                $query->where('scan_masuk', '!=', '00:00:00')
                      ->orWhere('scan_pulang', '!=', '00:00:00');
            })
            ->first(); 
    
        $data = [
            ['name' => 'Total Telat', 'y' => $rekapKehadiran->total_telat ?? 0],
            ['name' => 'Total Pulang Awal', 'y' => $rekapKehadiran->total_awal ?? 0]
        ];
    
        return $data;
    }    

    // FUNGSI UNTUK MENAMPILKAN 5 ORANG YANG TELAT TERBANYAK
    public function rekapTelatBulanan($bulan, $tahun)
    {
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);

        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
                'nama',
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
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
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

    // FUNGSI UNTUK MENAMPILKAN DATA DETAIL 5 TELAT TERBANYAK
    public function kehadiranDetail(Request $request)
    {
        $nama = $request->input('nama');
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

    
        $lateDetails = EmployeePresensi::select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
            ->where('nama', $nama)
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $year)
            ->whereTime('scan_masuk', '>', DB::raw("
                CASE jam_kerja 
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
                    WHEN 'Shift 3A 5 jam' THEN '00:00:00'
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
                END
            "))
            ->get();
    
        return view('dashboard.detailslateterbanyak', [
            'nama' => $nama,
            'lateDetails' => $lateDetails
        ]);
    }

    //FUNGSI UNTUK MELIHAT DETAILS LEAVE PERMINGGU
    public function LeaveMingguanDetail(Request $request)
    {
        $nama = $request->input('nama');
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
    
        $presensi = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'pengecualian')
            ->where('nama', $nama)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('pengecualian')
            ->where('pengecualian', '!=', '')
            ->where('pengecualian', '!=', 'DINAS LUAR')
            ->get();
    
        return view('dashboard.detailsleave', [
            'nama' => $nama,
            'presensi' => $presensi
        ]);
    }

    //FUNGSI UNTUK MELIHAT DETAILS LATE PERMINGGU
    public function LateMingguanDetail(Request $request)
    {
        $nama = $request->input('nama');
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Detail keterlambatan
        $lateDetails = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang', 'jam_kerja')
            ->where('nama', $nama)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereTime('scan_masuk', '>', DB::raw("
                CASE jam_kerja 
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
                    WHEN 'Shift 3A 5 jam' THEN '00:00:00'
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
                END
            "))
            ->get();

        return view('dashboard.detailslateterbanyak', [
            'nama' => $nama,
            'lateDetails' => $lateDetails
        ]);
    }

    public function getTotalPresensi($bulan, $tahun)
    {
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);

        $query = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')->where('pengecualian', 'DINAS LUAR')
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
            ->get();
        $totals = DB::table('employee_presensi_bulanan')
            ->select(
                DB::raw("SUM(CASE WHEN absent = 'TRUE' THEN 1 ELSE 0 END) as total_absent"),
                DB::raw("SUM(CASE WHEN pengecualian IN ('SAKIT', 'sakit dg srt dokter') THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN pengecualian = 'SAKIT TANPA SD' THEN 1 ELSE 0 END) as total_sakit_tanpa_sd"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI MELAHIRKAN' THEN 1 ELSE 0 END) as total_cuti_melahirkan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI TAHUNAN' THEN 1 ELSE 0 END) as total_cuti_tahunan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'DINAS LUAR' THEN 1 ELSE 0 END) as total_dinas_luar"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI' THEN 1 ELSE 0 END) as total_cuti"),
                DB::raw("SUM(CASE WHEN pengecualian = 'IZIN' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN pengecualian = 'ANAK BTIS/SUNAT' THEN 1 ELSE 0 END) as total_anak_btis"),
                DB::raw("SUM(CASE WHEN pengecualian = 'ISTRI MELAHIRKAN' THEN 1 ELSE 0 END) as total_istri_melahirkan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'MENIKAH' THEN 1 ELSE 0 END) as total_menikah"),
                DB::raw("SUM(CASE WHEN pengecualian = 'OT/MTUA/KLG MGL' THEN 1 ELSE 0 END) as total_ot_mtua_klg_mgl"),
                DB::raw("SUM(CASE WHEN pengecualian = 'WFH' THEN 1 ELSE 0 END) as total_wfh"),
                DB::raw("SUM(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 ELSE 0 END) as total_paruh_waktu")
            )
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
            ->first();
        
        return $totals;
    }

    //FUNGSI UNTUK MENAMPILKAN DETAILS DARI SETIAP KATEGORI LEAVE
    public function getLeaveDetail(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
            'status' => 'required|string',
        ]);

        $startDate = now()->setYear($request->tahun)->setMonth($request->bulan)->subMonth()->day(26);
        $endDate = now()->setYear($request->tahun)->setMonth($request->bulan)->day(25);

        $query = EmployeePresensi::select('nama', 'jam_kerja', 'tanggal', 'scan_masuk', 'scan_pulang', 'pengecualian')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $request->tahun); 

        if ($request->status == 'Sakit') {
            $query->whereIn('pengecualian', ['SAKIT', 'sakit dg srt dokter']);
        } elseif ($request->status == 'stsd') {
            $query->whereIn('pengecualian', ['SAKIT TANPA SD']);
        } elseif ($request->status == 'cuti') {
            $query->whereIn('pengecualian', ['CUTI']);
        } elseif ($request->status == 'izin') {
            $query->whereIn('pengecualian', ['IZIN']);
        } elseif ($request->status == 'dl') {
            $query->whereIn('pengecualian', ['DINAS LUAR']);
        } elseif ($request->status == 'ct') {
            $query->whereIn('pengecualian', ['CUTI TAHUNAN']);
        } elseif ($request->status == 'cm') {
            $query->whereIn('pengecualian', ['CUTI MELAHIRKAN']);
        } elseif ($request->status == 'nikah') {
            $query->whereIn('pengecualian', ['MENIKAH']);
        } elseif ($request->status == 'im') {
            $query->whereIn('pengecualian', ['ISTRI MELAHIRKAN']);
        } elseif ($request->status == 'as') {
            $query->whereIn('pengecualian', ['ANAK BTIS/SUNAT']);
        } elseif ($request->status == 'mgl') {
            $query->whereIn('pengecualian', ['OT/MTUA/KLG MGL']);
        } elseif ($request->status == 'wfh') {
            $query->whereIn('pengecualian', ['WFH']);
        } elseif ($request->status == 'pw') {
            $query->whereIn('pengecualian', ['PARUH WAKTU']);
        } elseif ($request->status == 'libur') {
            $query->whereIn('pengecualian', ['LIBUR']);
        }

        $lateDetails = $query->orderBy('tanggal', 'asc')->get(); 

        return view('dashboard.detailskategorileave', compact('lateDetails'));
    }


    public function getTotalLeave($bulan, $tahun)
    {
        $startDate = now()->setYear($tahun)->setMonth($bulan)->subMonth()->day(26);
        $endDate = now()->setYear($tahun)->setMonth($bulan)->day(25);

        $leaves = DB::table('employee_presensi_bulanan')
            ->select(
                DB::raw("SUM(CASE WHEN pengecualian IN ('SAKIT', 'sakit dg srt dokter') THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN pengecualian = 'SAKIT TANPA SD' THEN 1 ELSE 0 END) as total_sakit_tanpa_sd"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI MELAHIRKAN' THEN 1 ELSE 0 END) as total_cuti_melahirkan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI TAHUNAN' THEN 1 ELSE 0 END) as total_cuti_tahunan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'DINAS LUAR' THEN 1 ELSE 0 END) as total_dinas_luar"),
                DB::raw("SUM(CASE WHEN pengecualian = 'CUTI' THEN 1 ELSE 0 END) as total_cuti"),
                DB::raw("SUM(CASE WHEN pengecualian = 'IZIN' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN pengecualian = 'ANAK BTIS/SUNAT' THEN 1 ELSE 0 END) as total_anak_btis"),
                DB::raw("SUM(CASE WHEN pengecualian = 'ISTRI MELAHIRKAN' THEN 1 ELSE 0 END) as total_istri_melahirkan"),
                DB::raw("SUM(CASE WHEN pengecualian = 'MENIKAH' THEN 1 ELSE 0 END) as total_menikah"),
                DB::raw("SUM(CASE WHEN pengecualian = 'OT/MTUA/KLG MGL' THEN 1 ELSE 0 END) as total_ot_mtua_klg_mgl"),
                DB::raw("SUM(CASE WHEN pengecualian = 'WFH' THEN 1 ELSE 0 END) as total_wfh"),
                DB::raw("SUM(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 ELSE 0 END) as total_paruh_waktu"),
                DB::raw("
                SUM(
                    CASE 
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Crew Marketing' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Driver Ekspedisi S-J' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Driver Ekspedisi Sab' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Driver Ops' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Fleksibel' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Shift 1A 5 jam' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Shift 1C' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 7 THEN 1
                        WHEN absent = 'TRUE' AND pengecualian = '' 
                            AND jam_kerja = 'Staff Up 5 HK' AND DAYOFWEEK(tanggal) BETWEEN 2 AND 6 THEN 1
                        ELSE 0 
                    END
                ) as total_absent
            ")
            )
            ->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $tahun)
            ->first();

        return $leaves;
    }

    // FUNGSI UNTUK MENAMPILKAN DATA LEAVE TERBANYAK
    public function showLeaveDetails(Request $request)
    {

        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        $query = EmployeePresensi::select(
            'nama',
            'jam_kerja',
            'tanggal',
            'scan_masuk',
            'scan_pulang',
            'pengecualian'
        );

        $query->whereBetween('tanggal', [$startDate, $endDate])->whereYear('tanggal', $year);

        if ($request->input('status') === 'LEAVE') {
            $query->whereIn('pengecualian', [
                'SAKIT', 'sakit dg srt dokter', 'SAKIT TANPA SD', 'CUTI MELAHIRKAN', 
                'CUTI TAHUNAN', 'CUTI', 'IZIN', 'ANAK BTIS/SUNAT', 
                'ISTRI MELAHIRKAN', 'MENIKAH', 'OT/MTUA/KLG MGL', 
                'WFH', 'PARUH WAKTU'
            ]);
        }

        $presensi = $query->get();
        
        return view('dashboard.detailsleave', [
            'presensi' => $presensi
        ]);
    }

    // FUNGSI UNTUK MENAMPILKAN DATA 5 ORANG TERTELAT
    public function getLateRecords(Request $request)
    {
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);
        
        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        $query = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $year)
            ->whereRaw("TIME(scan_masuk) > TIME(CASE jam_kerja 
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
            END)")
            ->orderBy('tanggal', 'asc'); 

        $lateRecords = $query->get();
        
        return view('dashboard.detailslate', [
            'lateRecords' => $lateRecords
        ]);
    }

    //FUNGSI UNTUK MENAMPILKAN DATA DETAILS YANG PULANG LEBIH AWAL
    public function getAwalRecords(Request $request)
    {
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        $query = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang', 'pengecualian')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $year)
            ->where(function ($query) {
                $query->where('scan_masuk', '!=', '00:00:00')
                    ->orWhere('scan_pulang', '!=', '00:00:00');
            })
            ->whereRaw("TIME(scan_pulang) < TIME(CASE jam_kerja 
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
            END)");

        $awalRecords = $query->get();
        return view('dashboard.detailsawal', [
            'awalRecords' => $awalRecords
        ]);
    }

    //FUNGSI UNTUK MENAMPILKAN DATA YANG DINAS LUAR 
    public function getDinasLuarData(Request $request)
    {
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        // Hitung tanggal awal (26 bulan sebelumnya) dan tanggal akhir (25 bulan yang diminta)
        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        // Ambil data Dinas Luar dan urutkan secara descending berdasarkan tanggal
        $query = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
            ->where('pengecualian', 'DINAS LUAR')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'ASC')
            ->get();

        return view('dashboard.detailsdinasluar', [
            'dinasLuarDetails' => $query,
            'bulan' => $month,
            'tahun' => $year,
        ]);
    }

    //FUNGSI UNTUK MENAMPILKAN DATA DETAILS ABSENT
    public function getAbsentData(Request $request)
    {
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        $absentRecords = DB::table('employee_presensi_bulanan')
            ->where('absent', 'TRUE')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $year)
            ->where('pengecualian', '')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Crew Marketing')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Driver Ekspedisi S-J')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Driver Ekspedisi Sab')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Driver Ops')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); // Senin-Jumat
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Fleksibel')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Shift 1A 5 jam')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); // Senin-Sabtu
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Shift 1C')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 7]); 
                })->orWhere(function ($subQuery) {
                    $subQuery->where('jam_kerja', 'Staff Up 5 HK')
                            ->whereBetween(DB::raw('DAYOFWEEK(tanggal)'), [2, 6]); 
                });
            })
            ->orderBy('tanggal', 'asc') 
            ->get();

        return view('dashboard.detailsabsent', [
            'absentRecords' => $absentRecords,
            'bulan' => $month,
            'tahun' => $year,
        ]);
    }

    //FUNGSI UNTUK MENAMPILKAN DATA YANG WFH
    public function getWFH(Request $request)
    {
        $month = $request->input('bulan', now()->month);
        $year = $request->input('tahun', now()->year);

        $startDate = now()->setYear($year)->setMonth($month)->subMonth()->day(26);
        $endDate = now()->setYear($year)->setMonth($month)->day(25);

        $query = DB::table('employee_presensi_bulanan')
            ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
            ->where('pengecualian', 'WFH')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'ASC') 
            ->get();

        return view('dashboard.detailswfh', [
            'wfh' => $query,
            'bulan' => $month,
            'tahun' => $year,
        ]);
    }

}
