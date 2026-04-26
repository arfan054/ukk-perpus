<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // ===============================
    // 📚 ADMIN: LIHAT SEMUA BUKU
    // ===============================
    public function index()
    {
        $buku = Buku::all();
        return view('admin.buku', compact('buku'));
    }

    // ===============================
    // 👤 ANGGOTA: LIHAT BUKU
    // ===============================
    public function anggota()
    {
        $buku = Buku::all();
        return view('anggota.buku', compact('buku'));
    }

    // ===============================
    // ➕ TAMBAH BUKU
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'sampul' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $namaFile = null;

        if ($request->hasFile('sampul')) {
            $file = $request->file('sampul');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('sampul'), $namaFile);
        }

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

    // ===============================
    // ✏️ FORM EDIT
    // ===============================
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('admin.buku_edit', compact('buku'));
    }

    // ===============================
    // 🔄 UPDATE DATA
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'sampul' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $buku = Buku::findOrFail($id);

        // upload sampul baru
        if ($request->hasFile('sampul')) {
            $file = $request->file('sampul');
            $namaFile = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('sampul'), $namaFile);

            $buku->sampul = $namaFile;
        }

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.buku')->with('success', 'Buku berhasil diupdate!');
    }

    // ===============================
    // 🗑️ HAPUS BUKU
    // ===============================
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // hapus file sampul jika ada
        if ($buku->sampul && file_exists(public_path('sampul/' . $buku->sampul))) {
            unlink(public_path('sampul/' . $buku->sampul));
        }

        $buku->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }
}