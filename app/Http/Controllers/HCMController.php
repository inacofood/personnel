<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeHris;
use App\Models\EmployeePresensi;
use App\Models\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
class HCMController extends Controller
{
    public function index()
    {
        $presensi = EmployeePresensi::all();

        return view('presensi.index', [
            'presensi' => $presensi
        ]);
    }

    public function import()
    {
        $filePath = 'C:/Users/User/Desktop/Presensi/absen.xlsx';

        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            return "File tidak ditemukan di path: " . $filePath;
        }

        // Muat file Excel
        $spreadsheet = IOFactory::load($filePath);
        $allData = [];
        $employees = Employee::get();
        try {
        // Iterasi melalui setiap sheet
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            // Iterasi melalui setiap baris
            $sheet->removeRow(1, 1);
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Ini agar semua sel diiterasi, termasuk yang kosong

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $employees = Employee::where('registration_number',$rowData[1])->first();
                $employee_hris = EmployeeHris::where('NIK',$rowData[1])->first();
                if($employees){
                    $departments = Department::where('id',$employees->department_id)->first();
                    $direct_superior = $employees->direct_superior;
                    $nama_superior = Employee::where('direct_superior',$employees->direct_superior)->first();
                }elseif($employee_hris){
                    $departments = $employee_hris->department;
                    $direct_superior = $employee_hris->superior;
                    $nama_superior = Employee::where('direct_superior',$employee_hris->superior)->first();
                }else{
                    $departments = null;
                    $direct_superior =null;
                    $nama_superior = null;
                }
                $tanggal = isset($rowData[4]) ? Carbon::createFromFormat('d-M-y', $rowData[4])->format('Y-m-d') : null;
                $nik = $rowData[1] ?? null;

                // Periksa apakah data dengan NIK dan tanggal yang sama sudah ada
                $existingData = EmployeePresensi::where('nik', $nik)
                                                ->where('tanggal', $tanggal)
                                                ->first();

                $data = [
                    'nik' => $nik,
                    'nama' => $rowData[2] ?? null,
                    'department' => $departments->department_name ?? null,
                    'direct_superior' => $direct_superior ?? null,
                    'nama_superior' => $nama_superior->fullname ?? null,
                    'tanggal' => $tanggal,
                    'jamkerja' => $rowData[5] ?? null,
                    'masuk' => $rowData[6] ? $rowData[6].':00' : null,
                    'keluar' => $rowData[7] ? $rowData[7].':00' : null,
                    'terlambat' => $rowData[8] ? $rowData[8].':00' : null,
                    'pulangcepat' => $rowData[9] ? $rowData[9].':00' : null,
                    'pengecualian' => $rowData[11] ?? null,
                    'created_by' => 6,
                ];

                // Jika data sudah ada, lakukan update
                if ($existingData) {
                    EmployeePresensi::where('nik', $nik)
                                    ->where('tanggal', $tanggal)
                                    ->update($data);
                } else {
                    // Insert data baru
                    EmployeePresensi::insert($data);

                    // Periksa apakah waktu keluar kosong (tidak keluar)
                    if (!$data['keluar']) {
                        // Buat entri baru untuk hari berikutnya jika karyawan masuk kembali
                        $nextDay = Carbon::createFromFormat('d-M-y', $rowData[4])->addDay()->format('Y-m-d');

                        // Periksa apakah sudah ada entri untuk hari berikutnya
                        $nextDayData = EmployeePresensi::where('nik', $nik)
                                                    ->where('tanggal', $nextDay)
                                                    ->first();

                        if (!$nextDayData && $data['masuk']) {
                            // Insert data baru untuk hari berikutnya dengan waktu masuk, keluarnya null
                            $newData = [
                                'nik' => $nik,
                                'nama' => $rowData[2] ?? null,
                                'department' => $departments->department_name ?? null,
                                'direct_superior' => $direct_superior ?? null,
                                'nama_superior' => $nama_superior->fullname ?? null,
                                'tanggal' => $nextDay,
                                'jamkerja' => $rowData[5] ?? null,
                                'masuk' => null, // Masuknya akan diisi nanti, saat ada data masuk yang valid
                                'keluar' => null,
                                'terlambat' => null,
                                'pulangcepat' => null,
                                'pengecualian' => null,
                                'created_by' => 6,
                            ];

                            EmployeePresensi::insert($newData);
                        }
                    }
                }
                // $tanggal = isset($rowData[4]) ? Carbon::createFromFormat('d-M-y', $rowData[4])->format('Y-m-d') : null;
                // $nik = $rowData[1] ?? null;
                // // Periksa apakah data dengan NIK dan tanggal yang sama sudah ada
                // $existingData = EmployeePresensi::where('nik', $rowData[1])
                //                                 ->where('tanggal', $tanggal)
                //                                 ->first();
                // $data = [
                //         'nik' => $rowData[1] ?? null,
                //         'nama' => $rowData[2] ?? null,
                //         'department' =>  $departments->department_name ?? null,
                //         'direct_superior' => $direct_superior ?? null,
                //         'nama_superior' => $nama_superior->fullname ?? null,
                //         'tanggal' => Carbon::createFromFormat('d-M-y', $rowData[4])->format('Y-m-d') ?? null,
                //         'jamkerja' => $rowData[5] ?? null,
                //         'masuk' => $rowData[6] ? $rowData[6].':00' :  null,
                //         'keluar' => $rowData[7] ? $rowData[7].':00' :  null,
                //         'terlambat' => $rowData[8] ? $rowData[8].':00' :  null,
                //         'pulangcepat' => $rowData[9] ? $rowData[9].':00' :  null,
                //         'pengecualian' => $rowData[11] ?? null,
                //         'created_by' => 6,
                //     ];
                // // Masukkan data ke array sesuai dengan kolom yang diinginkan
                // if (!$existingData) {

                //     EmployeePresensi::insert($data);

                // }else{
                //     EmployeePresensi::where('nik', $nik)
                //     ->where('tanggal', $tanggal)
                //     ->update($data);
                // }
            }
        }
        // dd($data);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return "Error: " . $e->getMessage();
        }
        return redirect('/presensi')->with('alert', [
            'type' => 'success',
            'msg' => 'Berhasil menambahkan Kehadiran'
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

    // public function exportEmployee()
    // {
    //     return Excel::download(new BPJSParticipantExport, 'bpjs-participant-export-'.date('Y-m-d').'.xlsx');
    // }

    // public function exportEmployeeInactive()
    // {
    //     return Excel::download(new BPJSParticipantInactiveExport, 'bpjs-participant-inactive-export-'.date('Y-m-d').'.xlsx');
    // }


}
