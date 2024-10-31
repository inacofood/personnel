<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersRole;
use App\Models\EmployeePresensi;

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
            ->whereMonth('tanggal', $bulan) 
            ->whereYear('tanggal', $tahun)  
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
        $rekapKehadiran = DB::table('employee_presensi_bulanan')
            ->select(
                'nama',
                'pengecualian', 
                DB::raw("COUNT(CASE WHEN pengecualian IS NOT NULL AND pengecualian != '' AND pengecualian != 'DINAS LUAR' THEN 1 END) as total_leave")
            )
            ->whereMonth('tanggal', $bulan) 
            ->whereYear('tanggal', $tahun)  
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
    $nama = $request->input('nama'); 
    $kategoriLeave = $request->input('kategori_leave'); 

    $query = EmployeePresensi::select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year);
    
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
            ->whereMonth('tanggal', $bulan) 
            ->whereYear('tanggal', $tahun) 
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
            ->whereMonth('tanggal', $bulan) 
            ->whereYear('tanggal', $tahun)  
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
    
        $lateDetails = EmployeePresensi::select('nama', 'tanggal', 'scan_masuk', 'scan_pulang')
            ->where('nama', $nama)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
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
    $totals = DB::table('employee_presensi_bulanan')
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
            DB::raw("SUM(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 ELSE 0 END) as total_paruh_waktu")
        )
        ->whereMonth('tanggal', $bulan) 
        ->whereYear('tanggal', $tahun) 
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

    $query = EmployeePresensi::select('nama', 'jam_kerja', 'tanggal', 'scan_masuk', 'scan_pulang', 'pengecualian')
        ->whereMonth('tanggal', $request->bulan)
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

    $lateDetails = $query->get();

    return view('dashboard.detailskategorileave', compact('lateDetails'));
}

public function getTotalLeave($bulan, $tahun)
{
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
            DB::raw("SUM(CASE WHEN pengecualian = 'PARUH WAKTU' THEN 1 ELSE 0 END) as total_paruh_waktu")
        )
        ->whereMonth('tanggal', $bulan) 
        ->whereYear('tanggal', $tahun)
        ->first();

    return $leaves;
}

// FUNGSI UNTUK MENAMPILKAN DATA LEAVE TERBANYAK
public function showLeaveDetails(Request $request)
{

    $month = $request->input('bulan', now()->month);
    $year = $request->input('tahun', now()->year);

    $query = EmployeePresensi::select(
        'nama',
        'jam_kerja',
        'tanggal',
        'scan_masuk',
        'scan_pulang',
        'pengecualian'
    );

    $query->whereMonth('tanggal', $month)
          ->whereYear('tanggal', $year);

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

    $query = DB::table('employee_presensi_bulanan')
        ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang');

    $query->whereMonth('tanggal', $month)
          ->whereYear('tanggal', $year);

    $query->whereRaw("TIME(scan_masuk) > TIME(CASE jam_kerja 
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
    END)");

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

    $query = DB::table('employee_presensi_bulanan')
        ->select('nama', 'tanggal', 'scan_masuk', 'scan_pulang');

    $query->whereMonth('tanggal', $month)
          ->whereYear('tanggal', $year);

    $query->whereRaw("TIME(scan_pulang) < TIME(CASE jam_kerja 
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
    $bulan = $request->input('bulan', now()->month);
    $tahun = $request->input('tahun', now()->year);

    $dinasLuarDetails = DB::table('employee_presensi_bulanan')
        ->where('pengecualian', 'DINAS LUAR')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

    return view('dashboard.detailsdinasluar', [
        'dinasLuarDetails' => $dinasLuarDetails,
        'bulan' => $bulan,
        'tahun' => $tahun,
    ]);
}

}