<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Exports\VisitorExport;
use Maatwebsite\Excel\Facades\Excel;

class VisitorController extends Controller
{
    public function indexvisitor()
    {
        $visitor = Lists::all();

        return view('visitor.indexvisitor', [
            'visitor' => $visitor
        ]);
    }

    public function exportVisitor(Request $request)
    {
        $jenis = $request->query('jenis', 'All');
        $status = $request->query('status', 'All');
    
        return Excel::download(new VisitorExport($jenis, $status), 'visitor-data.xlsx');
    }
}

