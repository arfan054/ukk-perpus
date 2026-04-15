@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }}
        </h1>
        <p class="text-gray-500">Dashboard Anggota Perpustakaan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-gray-500">Total Peminjaman</h2>
            <p class="text-2xl font-bold text-indigo-600">{{ $riwayat->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-gray-500">Buku Sedang Dipinjam</h2>
            <p class="text-2xl font-bold text-green-600">
                {{ $riwayat->where('status', 'dipinjam')->count() }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-gray-500">Status Akun</h2>
            <p class="text-2xl font-bold {{ Auth::user()->status == 'aktif' ? 'text-green-600' : 'text-red-600' }}">
                {{ ucfirst(Auth::user()->status ?? 'Aktif') }}
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 bg-green-50 border border-green-200 p-4 rounded-xl text-green-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 bg-red-50 border border-red-200 p-4 rounded-xl text-red-700">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Daftar Buku Tersedia</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($buku as $item)
            <div class="bg-white p-4 rounded-xl shadow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $item->judul }}</h3>
                    <p class="text-gray-600 text-sm">Penulis: {{ $item->penulis }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $item->kategori }}</p>
                    <p class="text-sm mt-2">Stok: <span class="font-bold">{{ $item->stok }}</span></p>
                </div>
                
                @if($item->stok > 0)
                    <button onclick="openModal('{{ $item->id }}', '{{ $item->judul }}')" 
                        class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm transition">
                        Pinjam Buku
                    </button>
                @else
                    <button disabled class="mt-4 bg-gray-300 text-gray-500 py-2 rounded-lg text-sm cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-bold mb-4">Riwayat Pinjaman Saya</h2>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-gray-600 border-b">Buku</th>
                        <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Tgl Pinjam</th>
                        <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Batas Kembali</th>
                        <th class="p-4 text-sm font-semibold text-gray-600 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 border-b text-sm">
                            <p class="font-bold text-gray-800">{{ $r->buku->judul }}</p>
                        </td>
                        <td class="p-4 border-b text-sm text-center">
                            {{ $r->tanggal_pinjam ? \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-4 border-b text-sm text-center">
                            @if($r->tanggal_kembali)
                                @php
                                    $isTerlambat = \Carbon\Carbon::parse($r->tanggal_kembali)->isPast() && $r->status == 'dipinjam';
                                @endphp
                                <span class="{{ $isTerlambat ? 'text-red-600 font-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 border-b text-center">
                            @if($r->status == 'menunggu')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase italic">Menunggu ACC</span>
                            @elseif($r->status == 'dipinjam')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold uppercase">Dipinjam</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold uppercase">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500 italic">Belum ada riwayat peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalPinjam" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white p-6 rounded-xl w-full max-w-sm shadow-2xl">
        <h2 class="text-xl font-bold mb-2">Konfirmasi Pinjam</h2>
        <p class="text-gray-500 text-sm mb-4">Pastikan buku yang kamu pilih sudah benar.</p>

        <form method="POST" action="{{ route('pinjam.store') }}">
            @csrf
            <input type="hidden" name="buku_id" id="buku_id">
            
            <div class="mb-4 bg-indigo-50 p-3 rounded-lg">
                <p class="text-[10px] text-indigo-400 uppercase font-bold tracking-wider">Judul Buku</p>
                <p id="judul_buku" class="text-lg font-semibold text-indigo-800"></p>
            </div>

            <p class="text-xs text-gray-400 italic mb-6">
                *Batas waktu peminjaman adalah 7 hari setelah disetujui Admin.
            </p>

            <div class="flex gap-2">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 shadow-md transition">
                    Ajukan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id, judul) {
        document.getElementById('modalPinjam').classList.remove('hidden');
        document.getElementById('buku_id').value = id;
        document.getElementById('judul_buku').innerText = judul;
    }

    function closeModal() {
        document.getElementById('modalPinjam').classList.add('hidden');
    }
</script>
@endsection