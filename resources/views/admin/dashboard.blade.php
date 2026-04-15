@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Selamat Datang di Dashboard Admin</h1>
    <p class="text-gray-600">Pilih menu di samping untuk mulai mengelola data sistem.</p>
    
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="p-4 bg-blue-100 border-l-4 border-blue-500">
            <h3 class="font-bold text-blue-700">Total Buku</h3>
            <p class="text-2xl font-semibold">{{ $totalBuku }}</p>
        </div>

        <div class="p-4 bg-green-100 border-l-4 border-green-500">
            <h3 class="font-bold text-green-700">Total Transaksi</h3>
            <p class="text-2xl font-semibold">{{ $totalTransaksi }}</p>
        </div>

        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500">
            <h3 class="font-bold text-yellow-700">Total Anggota</h3>
            <p class="text-2xl font-semibold">{{ $totalAnggota }}</p>
        </div>
    </div>
</div>
@endsection