@extends('layouts.admin')

@section('title', 'Data Peminjaman')
@section('page_title', 'Transaksi Peminjaman')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-blue-500 text-center">
            <p class="text-xs text-gray-500 font-bold uppercase">Total Pinjam</p>
            <p class="text-xl font-bold">{{ $totalPinjam }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-yellow-500 text-center">
            <p class="text-xs text-gray-500 font-bold uppercase">Berjalan</p>
            <p class="text-xl font-bold text-yellow-600">{{ $berjalan }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-red-500 text-center">
            <p class="text-xs text-gray-500 font-bold uppercase">Menunggu ACC</p>
            <p class="text-xl font-bold text-red-600">{{ $menunggu }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-green-500 text-center">
            <p class="text-xs text-gray-500 font-bold uppercase">Selesai</p>
            <p class="text-xl font-bold text-green-600">{{ $selesai }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm">Peminjam</th>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm">Buku</th>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm text-center">Tgl Pinjam</th>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm text-center">Batas Kembali</th>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm text-center">Status</th>
                    <th class="p-4 font-semibold text-gray-700 border-b text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $t)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 border-b">
                        <p class="font-bold text-gray-800">{{ $t->user->name }}</p>
                        <p class="text-xs text-gray-500 italic">ID: {{ $t->user_id }}</p>
                    </td>
                    <td class="p-4 border-b">
                        <p class="text-sm font-medium">{{ $t->buku->judul }}</p>
                        <p class="text-[10px] text-gray-400">Kategori: {{ $t->buku->kategori }}</p>
                    </td>
                    <td class="p-4 border-b text-sm text-center">
                        {{ $t->tanggal_pinjam ? \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') : '-' }}
                    </td>
                    
                    <td class="p-4 border-b text-sm text-center">
                        @if($t->tanggal_kembali)
                            @php
                                $isTerlambat = \Carbon\Carbon::parse($t->tanggal_kembali)->isPast() && $t->status == 'dipinjam';
                            @endphp
                            <span class="{{ $isTerlambat ? 'text-red-600 font-extrabold' : '' }}">
                                {{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') }}
                            </span>
                            @if($isTerlambat)
                                <p class="text-[9px] text-red-500 font-bold uppercase leading-none">Terlambat!</p>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <td class="p-4 border-b text-center">
                        @if($t->status == 'menunggu')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-[10px] font-bold uppercase">Menunggu ACC</span>
                        @elseif($t->status == 'dipinjam')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-[10px] font-bold uppercase">Dipinjam</span>
                        @elseif($t->status == 'kembali')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-[10px] font-bold uppercase">Selesai</span>
                        @endif
                    </td>

                    <td class="p-4 border-b text-center">
                        @if($t->status == 'menunggu')
                            <form action="{{ route('admin.acc', $t->id) }}" method="POST">
                                @csrf
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition">
                                    Setujui (ACC)
                                </button>
                            </form>
                        @elseif($t->status == 'dipinjam')
                            <form action="{{ route('admin.kembali', $t->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition">
                                    Kembalikan
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic">No Action</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection