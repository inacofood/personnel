<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UsersRole;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = UsersRole::where('id_users', $user->id)->pluck('id_role');

        return view('dashboard.index', compact('roles'));
    }
}
