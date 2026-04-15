<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // tampil halaman login
    public function showLogin()
    {
        return view('login');
    }

    // proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // LOGIN
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // ❌ CEK STATUS AKUN
            if ($user->status === 'nonaktif') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh admin!'
                ]);
            }

            // 🔥 REDIRECT BERDASARKAN ROLE
            return match ($user->role) {

                'admin' => redirect()->route('admin.dashboard'),

                'anggota' => redirect()->route('anggota.dashboard'),

                default => redirect('/login'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}