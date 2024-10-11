<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Role;

class UserController extends Controller
{
    // Menampilkan semua pengguna beserta perannya
    public function index()
    {
        
        $users = Users::with('role')->orderBy('id', 'asc')->get();
        // dd($users);
        $roles = Role::all();
        // dd($roles);

        return view('permission.users', compact('users', 'roles'));
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Email harus unik
            'role' => 'required|integer', // Role harus berupa angka (ID dari role)
        ]);

        $data['password'] = bcrypt('asd');

        $user = Users::create($data);
        $user->role()->associate($data['role']);
        $user->save();

        return redirect()->back()->with('success', 'User created successfully!');
    }

    // Mengedit data pengguna berdasarkan ID
    public function edit($id)
    {
        $user = Users::with('role')->findOrFail($id);
        $roles = Role::all();

        return view('permission.users', compact('user', 'roles'));
    }

    // Memperbarui data pengguna berdasarkan ID
    public function update(Request $request)
    {
        // dd($request->user_id);
        $user = Users::findOrFail($request->user_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|integer', 
        ]);

        $user->update($data);
        $user->role()->associate($data['role']);
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // Menghapus pengguna berdasarkan ID
    public function destroy($id)
    {
        $user = Users::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
