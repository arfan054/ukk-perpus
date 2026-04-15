<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    // --- DASHBOARD & STATISTIK ---
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalTransaksi = Transaksi::count(); 

        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'totalTransaksi'));
    }

    // --- MANAJEMEN BUKU (CRUD) ---
    public function buku(Request $request)
    {
        $query = $request->input('search');
        $buku = Buku::when($query, function ($q) use ($query) {
            return $q->where('judul', 'like', "%{$query}%")
                     ->orWhere('penulis', 'like', "%{$query}%");
        })->get();

        return view('admin.buku', compact('buku', 'query'));
    }

    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('sampul')) {
            $file = $request->file('sampul');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('sampul'), $fileName);
        }

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'rak_lokasi' => $request->rak_lokasi,
            'deskripsi' => $request->deskripsi,
            'sampul' => $fileName,
        ]);

        return redirect()->route('admin.buku')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $fileName = $buku->sampul;

        if ($request->hasFile('sampul')) {
            if ($buku->sampul && File::exists(public_path('sampul/'.$buku->sampul))) {
                File::delete(public_path('sampul/'.$buku->sampul));
            }
            $file = $request->file('sampul');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('sampul'), $fileName);
        }

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'tahun_terbit' => $request->tahun_terbit,
            'stok' => $request->stok,
            'sampul' => $fileName,
        ]);

        return redirect()->route('admin.buku')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->sampul && File::exists(public_path('sampul/'.$buku->sampul))) {
            File::delete(public_path('sampul/'.$buku->sampul));
        }
        $buku->delete();
        return redirect()->route('admin.buku')->with('success', 'Buku berhasil dihapus');
    }

    // --- SISTEM TRANSAKSI ---
    public function transaksi()
    {
        $transaksi = Transaksi::with(['user', 'buku'])->latest()->get();
        $totalPinjam = Transaksi::count();
        $berjalan = Transaksi::where('status', 'dipinjam')->count();
        $menunggu = Transaksi::where('status', 'menunggu')->count(); 
        $selesai = Transaksi::where('status', 'kembali')->count();

        return view('admin.transaksi', compact('transaksi', 'totalPinjam', 'berjalan', 'menunggu', 'selesai'));
    }

    public function pinjamStore(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id'
        ]);

        $userId = Auth::id();
        $bukuId = $request->buku_id;

        $cekDouble = Transaksi::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        if ($cekDouble) {
            return redirect()->back()->with('error', 'Kamu sudah mengajukan atau sedang meminjam buku ini!');
        }

        $buku = Buku::find($bukuId);
        if ($buku->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku baru saja habis!');
        }

        Transaksi::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Berhasil mengajukan pinjaman! Menunggu konfirmasi Admin.');
    }

    public function accPinjaman($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->buku->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $transaksi->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(7),
        ]);
        
        $transaksi->buku->decrement('stok');
        return redirect()->back()->with('success', 'Peminjaman disetujui!');
    }

    public function kembaliBuku($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status' => 'kembali',
            'tanggal_kembali' => Carbon::now() // Penting agar tgl kembali muncul di riwayat anggota
        ]);

        $transaksi->buku->increment('stok');
        return redirect()->back()->with('success', 'Buku telah dikembalikan.');
    }
}