<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Exports\TemuangaExport; 
use Maatwebsite\Excel\Facades\Excel;

class TemuanGAController extends Controller{
    
    public function indextemuanga()
    {
        $temuan = Complaint::all();

        return view('temuanga.indextemuanga', [
            'temuan' => $temuan
        ]);
    }

    public function export(Request $request)
    {
        $status = $request->query('status');
        return Excel::download(new TemuangaExport($status), 'data_temuan.xlsx');
    }

}