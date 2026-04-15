@extends('layouts.anggota')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buku Yang Sudah Dikembalikan</h1>
        <p class="text-gray-500">Berikut adalah daftar buku yang telah selesai kamu pinjam.</p>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600 border-b">Buku</th>
                    <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Tgl Pinjam</th>
                    <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Tgl Kembali</th>
                    <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatKembali as $r)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 border-b text-sm">
                        <p class="font-bold text-gray-800">{{ $r->buku->judul ?? 'Buku tidak ditemukan' }}</p>
                        <p class="text-xs text-gray-400">{{ $r->buku->kategori }}</p>
                    </td>
                    <td class="p-4 border-b text-sm text-center">
                        {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d/m/Y') }}
                    </td>
                    <td class="p-4 border-b text-sm text-center">
                        <span class="text-indigo-600 font-medium">
                            {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="p-4 border-b text-center">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase">
                            Selesai
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400 italic">
                        Kamu belum memiliki riwayat pengembalian buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">

    <div class="p-4 border-b bg-gray-50">
        <h2 class="font-bold text-gray-700">Buku yang Sedang Dipinjam</h2>
    </div>

    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 text-sm">Buku</th>
                <th class="p-4 text-sm text-center">Tgl Pinjam</th>
                <th class="p-4 text-sm text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($riwayatDipinjam as $r)
            <tr class="hover:bg-gray-50">
                <td class="p-4">
                    <p class="font-bold text-gray-800">
                        {{ $r->buku->judul ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $r->buku->kategori ?? '-' }}
                    </p>
                </td>

                <td class="p-4 text-center">
                    {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d/m/Y') }}
                </td>

                <td class="p-4 text-center">
                    <form method="POST" action="{{ route('admin.kembali', $r->id) }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white text-xs px-4 py-2 rounded-lg font-bold">
                            Kembalikan
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="p-6 text-center text-gray-400 italic">
                    Tidak ada buku yang sedang dipinjam
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
    
    <div class="mt-6">
        <a href="{{ route('anggota.dashboard') }}" class="text-indigo-600 hover:underline text-sm font-medium">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection