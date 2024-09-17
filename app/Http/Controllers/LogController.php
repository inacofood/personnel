<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListLog;
use App\Models\ListProject;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListLogsExport;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function addLog(Request $request)
    {
        $auth = Auth::user();
        $list = ListProject::where('id', '=', $request->project_id)->first();


        if (!$list) {
            return redirect()->back()->with('error', 'Job not found');
        }

        // Get the input data from the request

        $tanggal = date_create_from_format('d-m-Y', $request->tgl);
        $tanggal = $tanggal->format('Y-m-d');

        // $tanggal = $request->tgl;
        $keterangan = $request->keterangan;
        $kategori = $request->kategori;

        // Perform any additional validation if needed

        try {
            $log = new ListLog();
            $log->project_id = $list->id;
            $log->updater_id = $auth->id;
            $log->tgl = $tanggal;
            $log->kategori = $kategori;
            $log->keterangan = $keterangan;
            $log->save();

            return redirect()->back()->with('success', 'Log saved successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to save log: ' . $e->getMessage());
        }
    }


    public function deleteLog($id)
    {
        try {
            $item = ListLog::findOrFail($id);
            $item->delete();

            return back()->with('success', 'Log Deleted Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to Delete Log: '. $e->getMessage());
        }
    }

}



