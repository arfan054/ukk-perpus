<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // ADMIN: lihat buku
    public function index()
    {
        $buku = Buku::all();
        return view('admin.buku', compact('buku'));
    }

    // ANGGOTA: lihat buku
    public function anggota()
    {
        $buku = Buku::all();
        return view('anggota.buku', compact('buku'));
    }

    // ADMIN: tambah buku
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'sampul' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // 2. Logika Upload Sampul
        $namaFile = null;
        if ($request->hasFile('sampul')) {
            $file = $request->file('sampul');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('sampul'), $namaFile);
        }

        // 3. Simpan ke Database (INI YANG TADI HILANG)
        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'sampul' => $namaFile,
            'status' => 'tersedia'
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }
}