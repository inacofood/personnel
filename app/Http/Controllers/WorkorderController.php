<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Exports\VisitorExport;
use Maatwebsite\Excel\Facades\Excel;

class WorkorderController extends Controller
{
    public function indexworkorder()
    {
        
        $workorder = Orders::all();

        return view('workorder.indexworkorder', [
            'workorder' => $workorder
        ]);

    }

    public function exportWorkorder(Request $request)
    {
        $jenis = $request->query('jenis', 'All');
        $status = $request->query('status', 'All');
    
        return Excel::download(new WorkorderExport($jenis, $status), 'visitor-data.xlsx');
    }
}

