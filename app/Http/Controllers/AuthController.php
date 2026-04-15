<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------
    | 🔐 LOGIN
    |--------------------------------------------------
    */

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

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // ❌ CEK STATUS
            if ($user->status === 'nonaktif') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh admin!'
                ]);
            }

            // 🔥 REDIRECT ROLE
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

    /*
    |--------------------------------------------------
    | 📝 REGISTER
    |--------------------------------------------------
    */

    // tampil halaman register
    public function showRegister()
    {
        return view('register'); // pastikan file: resources/views/register.blade.php
    }

    // proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ]);

        // simpan ke tabel users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota', // default anggota
            'status' => 'aktif'  // langsung aktif
        ]);

        // simpan ke tabel anggota (opsional tapi kamu pakai)
        Anggota::create([
            'user_id' => $user->id,
            'nama' => $request->name,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    /*
    |--------------------------------------------------
    | 🚪 LOGOUT
    |--------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}