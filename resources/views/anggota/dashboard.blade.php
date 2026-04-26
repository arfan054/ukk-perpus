@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="p-6">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Selamat Datang, {{ Auth::user()->name }} 👋
        </h1>
        <p class="text-gray-500">Ringkasan aktivitas perpustakaan Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 bg-indigo-50 rounded-xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Buku Sedang Dipinjam</p>
                <p class="text-4xl font-extrabold text-gray-800">{{ $riwayat->where('status', 'dipinjam')->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 bg-emerald-50 rounded-xl mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Riwayat Pinjam</p>
                <p class="text-4xl font-extrabold text-gray-800">{{ $riwayat->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg text-white">
            <h2 class="text-lg font-bold mb-2">Informasi Akun</h2>
            <div class="space-y-3 mt-4">
                <div class="flex justify-between border-b border-indigo-500 pb-2">
                    <span class="text-indigo-200">Nama</span>
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between border-b border-indigo-500 pb-2">
                    <span class="text-indigo-200">Status</span>
                    <span class="font-bold uppercase italic">{{ Auth::user()->status ?? 'Aktif' }}</span>
                </div>
            </div>
            <p class="mt-6 text-xs text-indigo-100 italic">*Hubungi petugas jika ada kendala pada status akun Anda.</p>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Riwayat Terakhir</h2>
                    <a href="#" class="text-xs text-indigo-600 font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Buku</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-center">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($riwayat->take(5) as $r)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">{{ $r->buku->judul }}</p>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ $r->tanggal_pinjam ? \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($r->status == 'menunggu')
                                        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-[10px] font-bold uppercase">Menunggu</span>
                                    @elseif($r->status == 'dipinjam')
                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded text-[10px] font-bold uppercase">Dipinjam</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400 italic text-sm">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection