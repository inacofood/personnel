<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $roles = Role::all();
        return view('permission.role', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:255',
        ]);
        Role::create([
            'department_name' => $validatedData['department_name'],
        ]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $roles = Role::findOrFail($id);
        $roles->delete();
        return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }
}


