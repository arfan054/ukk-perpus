<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\User;
use App\Models\Buku;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 👤 SISI ANGGOTA (Dashboard & Riwayat)
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $userId = Auth::id();
        $buku = Buku::all(); // Untuk daftar buku yang bisa dipinjam
        
        // Ambil riwayat yang BELUM selesai (menunggu & dipinjam)
        $riwayat = Transaksi::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->with('buku')
            ->latest()
            ->get();

        return view('anggota.dashboard', compact('buku', 'riwayat'));
    }

   public function pengembalian()
{
    $userId = Auth::id();

    // 🔵 Buku yang sedang dipinjam
    $riwayatDipinjam = Transaksi::where('user_id', $userId)
        ->where('status', 'dipinjam')
        ->with('buku')
        ->latest()
        ->get();

    // 🟢 Buku yang sudah dikembalikan
    $riwayatKembali = Transaksi::where('user_id', $userId)
        ->where('status', 'kembali')
        ->with('buku')
        ->latest()
        ->get();

    return view('anggota.pengembalian', compact(
        'riwayatDipinjam',
        'riwayatKembali'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | 🛠️ SISI ADMIN (Manajemen Data Anggota)
    |--------------------------------------------------------------------------
    */
    
    public function index()
    {
        $anggota = Anggota::all();
        return view('admin.anggota', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ]);

        // Buat User untuk Login
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'status' => 'aktif' 
        ]);

        // Simpan Detail Anggota
        Anggota::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'status' => 'aktif',
            'user_id' => $user->id
        ]);

        return redirect()->route('admin.anggota')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('admin.edit_anggota', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Update di tabel anggota
        $anggota->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'status' => $request->status, 
        ]);

        // Update juga di tabel users supaya loginnya sinkron
        if ($anggota->user_id) {
            User::where('id', $anggota->user_id)->update([
                'name' => $request->nama,
                'email' => $request->email,
                'status' => $request->status, 
            ]);
        }

        return redirect()->route('admin.anggota')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        // Hapus usernya dulu baru anggotanya
        if ($anggota->user_id) {
            User::where('id', $anggota->user_id)->delete();
        }

        $anggota->delete();

        return redirect()->route('admin.anggota')
            ->with('success', 'Data berhasil dihapus');
    }
}