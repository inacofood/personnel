<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Perusahaan;
use App\Exports\InvoiceExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Carbon\Carbon;
use App\Models\UsersRole;
use Illuminate\Support\Facades\Auth;

class MonitoringinvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = UsersRole::where('id_users', $user->id)->pluck('id_role')->first(); 

        $invoices = Invoice::all();
        return view('monitoringinvoice.invoice', compact('invoices'));
    }

    public function detail($id)
    {
        $invoices = Invoice::where('id', $id)->get();
        foreach ($invoices as $invoice) {
            $prev = Invoice::where('id', '<', $invoice->id)->max('id');
            $next = Invoice::where('id', '>', $invoice->id)->min('id');
        }
        return view('monitoringinvoice.detail', compact('invoices', 'prev', 'next'));
    }
    
    public function delete($id)
    {
        $invoices = Invoice::findOrFail($id);
        $invoices->delete();
        return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }

    public function show()
    {
        $data['perusahaan'] = Perusahaan::orderBy('nama_perusahaan','asc')->get();
        return view('monitoringinvoice.addinvoice',$data);
    }

    public function add(Request $request)
    {
        Invoice::insert([
            'id_invoice'                        =>  $request->id_invoice,
            'nominal'                           =>  preg_replace("/[^0-9]/", "", $request->nominal),
            'nama_perusahaan'                   =>  $request->nama_perusahaan,
            'keterangan'                        =>  $request->keterangan,
            'tipe'                              =>  $request->tipe,
            'kategori'                          =>  $request->kategori,
            'section'                           =>  $request->section,
            'no_pp'                             =>  $request->no_pp,
            'no_pr'                             =>  $request->no_pr,
            'tgl_resepsionis_terima'            =>  $request->tgl_resepsionis_terima,
            'tgl_sign_pp_invoice'               =>  $request->tgl_sign_pp_invoice,
            'tgl_input_pr_sap'                  =>  $request->tgl_input_pr_sap,
            'tgl_approve_pr_direksi'            =>  $request->tgl_approve_pr_direksi,
            'tgl_invoice_hcm_ke_finance'        =>  $request->tgl_invoice_hcm_ke_finance,
            'tgl_email_ke_ga'                   =>  $request->tgl_email_ke_ga,
            'tgl_ses_user'                      =>  $request->tgl_ses_user,
            'tgl_rilis_ses_user'                =>  $request->tgl_rilis_ses_user,
            'tgl_bayar'                         =>  $request->tgl_bayar,
        ]);
        return redirect()->action([MonitoringinvoiceController::class, 'index'])->with('success', 'Invoice berhasil disimpan!');
    }

    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->get();
        return view('monitoringinvoice.edit', compact('invoices'));
    }

    public function updateinvoice(Request $request)
    {
    if ($request->kategori == 'nonpr') {
        $db_up = Invoice::where('id', $request->id)
            ->update([
                'id_invoice'                        =>  $request->id_invoice,
                'nominal'                           =>  preg_replace("/[^0-9]/", "", $request->nominal),
                'nama_perusahaan'                   =>  $request->nama_perusahaan,
                'keterangan'                        =>  $request->keterangan,
                'tipe'                              =>  $request->tipe,
                'kategori'                          =>  $request->kategori,
                'section'                           =>  $request->section,
                'no_pp'                             =>  $request->no_pp,
                'tgl_resepsionis_terima'            =>  $request->tgl_resepsionis_terima,
                'tgl_sign_pp_invoice'               =>  $request->tgl_sign_pp_invoice,
                'tgl_input_pr_sap'                  =>  null,  
                'tgl_approve_pr_direksi'            =>  $request->tgl_approve_pr_direksi,
                'tgl_invoice_hcm_ke_finance'        =>  $request->tgl_invoice_hcm_ke_finance,
                'tgl_email_ke_ga'                   =>  null,  
                'tgl_ses_user'                      =>  null,  
                'tgl_rilis_ses_user'                =>  null,  
                'tgl_bayar'                         =>  $request->tgl_bayar,
            ]);
    } else {
        $db_up = Invoice::where('id', $request->id)
            ->update([
                'id_invoice'                        =>  $request->id_invoice,
                'nominal'                           =>  preg_replace("/[^0-9]/", "", $request->nominal),
                'nama_perusahaan'                   =>  $request->nama_perusahaan,
                'keterangan'                        =>  $request->keterangan,
                'tipe'                              =>  $request->tipe,
                'kategori'                          =>  $request->kategori,
                'section'                           =>  $request->section,
                'no_pp'                             =>  $request->no_pp,
                'no_pr'                             =>  $request->no_pr,
                'tgl_resepsionis_terima'            =>  $request->tgl_resepsionis_terima,
                'tgl_sign_pp_invoice'               =>  $request->tgl_sign_pp_invoice,
                'tgl_input_pr_sap'                  =>  $request->tgl_input_pr_sap,
                'tgl_approve_pr_direksi'            =>  $request->tgl_approve_pr_direksi,
                'tgl_invoice_hcm_ke_finance'        =>  $request->tgl_invoice_hcm_ke_finance,
                'tgl_email_ke_ga'                   =>  $request->tgl_email_ke_ga,
                'tgl_ses_user'                      =>  $request->tgl_ses_user,
                'tgl_rilis_ses_user'                =>  $request->tgl_rilis_ses_user,
                'tgl_bayar'                         =>  $request->tgl_bayar,
            ]);
    }

    return redirect()->action([MonitoringinvoiceController::class, 'index'])->with('success', 'Invoice berhasil diupdate!');
    }

    public function showexport()
    {
        return view('monitoringinvoice.invoice');
    }

    public function downloadexcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);
    
        $start_date = $request->start_date;
        $end_date = $request->end_date;
    
        return Excel::download(new InvoiceExport($start_date, $end_date), 'Monitoring-Invoice-export.xlsx');
    }
    

    
}
