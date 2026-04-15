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

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // 🔥 penting biar aman

            $user = Auth::user();

            // ❌ CEK STATUS
            if ($user->status === 'nonaktif') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh admin!'
                ]);
            }

            // 🔥 REDIRECT ROLE
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'anggota') {
                return redirect()->route('anggota.dashboard');
            }

            return redirect('/login');
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

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'status' => 'aktif'
        ]);

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