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

    public function exportvisitor()
    {
        return Excel::download(new VisitorExport, 'export_visitor.xlsx');
    }
}
