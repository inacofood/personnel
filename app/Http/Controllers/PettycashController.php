<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCC;
use App\Exports\PettycashExport;
use App\Exports\PettycashinExport;
use App\Exports\PettycashinPettycashExport;
use App\Models\Pettycash;
use App\Models\PettycashIn;
use App\Models\Kategori;
use App\Models\CostCenter;
use App\Models\Satuan;
use Auth;
use PDF;
use DB;

class PettycashController extends Controller
{
    public function index()
    {
        $data = Pettycash::with(['kategori', 'costCenter'])->get();
        $cc = CostCenter::all();
        $data2 = PettycashIn::all();
        $kategori = Kategori::all();
        $totalKlr = Pettycash::sum('total');
        $totalMsk = PettycashIn::sum('total');
        $total = $totalMsk - $totalKlr;

        return view('pettycash.home', compact('data', 'cc', 'kategori', 'data2', 'totalKlr', 'totalMsk', 'total'));
    }

    public function inputindex() {
        $kategori = Kategori::orderBy('name_kat', 'ASC')->get();
        $satuan = Satuan::all();
        $cc = CostCenter::all();

        return view('pettycash.input', compact('kategori', 'cc','satuan'));
    }
    

    public function insertpengeluaran(Request $req) {
        $HargaSatuan = str_replace(".", "", $req->harga_stn);
        $qty = $req->qty;
        $total = $HargaSatuan * $qty;

        if ($req->hasFile('file')) {
            $filename = $req->file('file');
            $uniqueFilename = pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME) . " (" . date('d-m-Y') . ") - " . uniqid() . "." . $filename->getClientOriginalExtension();
            $filename->move('uploads/kwitansi/', $uniqueFilename);

            Pettycash::create([
                'tgl' => $req->tgl,
                'uraian' => $req->uraian,
                'kategori_id' => $req->kategori_id,
                'qty' => $req->qty,
                'stn' => $req->stn,
                'harga_stn' => $HargaSatuan,
                'total' => $total,
                'cost_center_id' => $req->cost_center_id,
                'ket' => $req->ket,
                'filename' => $uniqueFilename,
            ]);
        } else {
            Pettycash::create([
                'tgl' => $req->tgl,
                'uraian' => $req->uraian,
                'kategori_id' => $req->kategori_id,
                'qty' => $qty,
                'stn' => $req->stn,
                'harga_stn' => $HargaSatuan,
                'total' => $total,
                'cost_center_id' => $req->cost_center_id,
                'ket' => $req->ket,
            ]);
        }
    
        return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pengeluaran berhasil disimpan!');
    }
    

    public function insertpemasukan(Request $req) {
        $total = str_replace(".", "", $req->totalPemasukan);

        PettycashIn::create([
            'tgl' => $req->tglPemasukan,
            'uraian' => $req->uraianPemasukan,
            'total' => $total,
            'ket' => $req->ketPemasukan,
        ]);

        return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pemasukan berhasil disimpan!');
    }

    public function updatepengeluaran(Request $req)
{

    $id = $req->idEdit;
    $HargaSatuan = str_replace(".", "", $req->harga_stnEdit);
    $Total = $req->qtyEdit * $HargaSatuan;

    $model = Pettycash::find($id);
    if (!$model) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
  
    $model->tgl = $req->tglEdit;
    $model->uraian = $req->uraianEdit;
    $model->kategori_id = $req->kategori_idEdit;
    $model->qty = $req->qtyEdit;
    $model->stn = $req->stnEdit;
    $model->harga_stn = $HargaSatuan;
    $model->total = $Total;
    $model->cost_center_id = $req->cost_centerEdit;
    $model->ket = $req->ketEdit;
    $model->save();

    return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pengeluaran berhasil diupdate!');
}

    public function updatepemasukan(Request $req)
    {
        $id = $req->idEdit;

        $HargaSatuan = str_replace(".", "", $req->harga_stnEdit);
        $Total = str_replace(".", "", $req->totalEdit);

        $model = PettycashIn::find($id);
        $model->tgl = $req->tglEdit;
        $model->uraian = $req->uraianEdit;
        $model->total = $Total;
        $model->ket = $req->ketEdit;
        $model->save();

        return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pemasukan berhasil diupdate!');
    }

    public function deletepengeluaran(Request $req)
    {

    Pettycash::destroy($req->idDel);
    return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pengeluaran berhasil didelete!');
    }

    public function deletepemasukan(Request $req)
    {

        PettycashIn::destroy($req->idDel);
        return redirect()->action([PettycashController::class, 'index'])->with('success', 'Pemasukan berhasil didelete!');
    }

    public function indexexport()
    {
        return view('pettycash.export');
    }

    public function exportexcel(Request $req)
        {
            $req->validate([
                'start_date' => 'bail|nullable|date',
                'end_date' => 'bail|nullable|date',
            ]);

            $params = [
                'start_date' => $req->start_date,
                'end_date' => $req->end_date
            ];

            return Excel::download(new PettycashExport($params), 'PettyCashPengeluaran-'.date('d-m-Y').'.xlsx');
        }


    public function exportinexcel(Request $req)
    {
        $req->validate([
            'start_date' => 'bail|nullable|date',
            'end_date' => 'bail|nullable|date',
        ]);

        $params = [
            'start_date' => $req->start_date,
            'end_date' => $req->end_date
        ];

    return Excel::download(new PettycashinExport($params), 'PettyCashPemasukan-'.date('d-m-Y').'.xlsx');
    }

    public function exportallexcel(Request $req)
    {
        $req->validate([
            'start_date' => 'bail|nullable|date',
            'end_date' => 'bail|nullable|date',
        ]);
    
        $params = [
            'start_date' => $req->start_date,
            'end_date' => $req->end_date
        ];
    
        return Excel::download(new PettycashinPettycashExport($params), 'PettyCash-'.date('d-m-Y').'.xlsx');
    }
    

    public function exportpp(Request $req)
{
    $today = date("d/m/Y");
    $date_ket = date("j F Y");

    $totalKlr = Pettycash::sum('total'); 
    $totalMsk = PettycashIn::sum('total'); 
    $saldo_temp = $totalMsk - $totalKlr;
    $saldo = 9000000 - $saldo_temp; 
    $saldo_terbilang = $this->terbilang($saldo) . " Rupiah";

    $sdate = date("d", strtotime($req->start_date)); 
    $edate = date("j F Y", strtotime($req->end_date)); 

    $pdf = PDF::loadView('pettycash.cetakpp', compact('today', 'date_ket', 'saldo', 'sdate', 'edate', 'saldo_terbilang'))
        ->setPaper('a4', 'portrait'); 
    return $pdf->stream(); 
}


    public function indeximport()
    {
        return view('home.import');
    }

    public function importcc(Request $req)
    {
        Excel::import(new Importcc, $req->file('file'));
        return back()->with('message', [
            'type' => 'Berhasil diimport!',
            'msg' => 'Berhasil!',
        ]);
    }

    private function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[$number];
        } else if ($number < 20) {
            $temp = $this->terbilang($number - 10) . " belas";
        } else if ($number < 100) {
            $temp = $this->terbilang($number / 10) . " puluh" . $this->terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " seratus" . $this->terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = $this->terbilang($number / 100) . " ratus" . $this->terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " seribu" . $this->terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = $this->terbilang($number / 1000) . " ribu" . $this->terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = $this->terbilang($number / 1000000) . " juta" . $this->terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = $this->terbilang($number / 1000000000) . " milyar" . $this->terbilang($number % 1000000000);
        } else if ($number < 1000000000000000) {
            $temp = $this->terbilang($number / 1000000000000) . " triliun" . $this->terbilang($number % 1000000000000);
        }

        return ucwords($temp);
    }



}
