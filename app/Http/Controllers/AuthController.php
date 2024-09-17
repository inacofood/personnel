<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
     
        return view('login.index');
    }

    public function login(Request $request)
    {
   
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Jika autentikasi berhasil, redirect ke halaman yang sesuai
            return redirect()->intended(route('dashboard.payroll'));
        }

        // Jika autentikasi gagal, redirect kembali ke halaman login dengan pesan error
        return redirect()->route('login')->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
