<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersRole;
use App\Models\Role;
use App\Models\Users;

class UsersroleController extends Controller
{
    public function index()
    {
        $usersRoles = UsersRole::with('user', 'role')
            ->get()
            ->groupBy('id_users')
            ->map(function ($groupedRoles) {
                $user = $groupedRoles->first()->user;
                $roles = $groupedRoles->pluck('role.department_name')->implode(', ');
                return (object)[
                    'user' => $user,
                    'roles' => $roles,
                    'id_users' => $groupedRoles->first()->id_users,
                    'id_users_role' => $groupedRoles->first()->id_users_role,
                ];
            });
    
        $roles = Role::all(); 
        $users = Users::all();
    
        return view('permission.usersrole', compact('usersRoles', 'roles', 'users'));
    }
    
    public function store(Request $request)
    {
        // Validasi input, memastikan 'roles' adalah array
        $request->validate([
            'id_users' => 'required',
            'roles' => 'required|array', // validasi bahwa roles adalah array
        ]);
    
        // Hapus semua roles sebelumnya jika ada
        UsersRole::where('id_users', $request->id_users)->delete();
    
        // Simpan data user role untuk setiap role yang dipilih
        foreach ($request->roles as $role) {
            UsersRole::create([
                'id_users' => $request->id_users,
                'id_role' => $role,
            ]);
        }
    
        return redirect()->back()->with('success', 'User role berhasil ditambahkan.');
    }
    
    public function edit($id)
{
    $userRoles = UsersRole::with('role')->where('id_users', $id)->get();
    $selectedRoles = $userRoles->pluck('id_role')->toArray();

    if ($userRoles->isEmpty()) {
        return response()->json(['error' => 'User roles tidak ditemukan'], 404);
    }

    return response()->json([
        'userRole' => $userRoles->first()->id_users,
        'roles' => $selectedRoles
    ]);
}
    
public function update(Request $request, $id)
{
    $request->validate([
        'id_users' => 'required',
        'roles' => 'required|array',
    ]);

    // Hapus role sebelumnya
    UsersRole::where('id_users', $request->id_users)->delete();

    // Tambah role baru
    foreach ($request->roles as $role) {
        UsersRole::create([
            'id_users' => $request->id_users,
            'id_role' => $role,
        ]);
    }

    return redirect()->back()->with('success', 'User role berhasil diperbarui.');
}


    public function destroy($id_users)
    {
        UsersRole::where('id_users', $id_users)->delete();

        return redirect()->back()->with('success', 'User roles deleted successfully!');
    }
}