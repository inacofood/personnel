<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class TemuanGAController extends Controller{
    
    public function indextemuanga()
    {
        $temuan = Complaint::all();

        return view('temuanga.indextemuanga', [
            'temuan' => $temuan
        ]);
    }



}