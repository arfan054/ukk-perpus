@extends('layouts.admin')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Buku</h2>

<form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="text" name="judul" value="{{ $buku->judul }}" class="w-full mb-2 p-2 border rounded">
    <input type="text" name="penulis" value="{{ $buku->penulis }}" class="w-full mb-2 p-2 border rounded">

    <input type="number" name="tahun_terbit" value="{{ $buku->tahun_terbit }}" class="w-full mb-2 p-2 border rounded">
    <input type="number" name="stok" value="{{ $buku->stok }}" class="w-full mb-2 p-2 border rounded">
    <select name="kategori" class="w-full border p-2 rounded">
    <option value="Fiksi" {{ $buku->kategori == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
    <option value="Non-Fiksi" {{ $buku->kategori == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
    <option value="Sains" {{ $buku->kategori == 'Sains' ? 'selected' : '' }}>Sains</option>
    <option value="Teknologi" {{ $buku->kategori == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
</select>

    <!-- preview -->
    @if($buku->sampul)
        <img src="{{ asset('sampul/'.$buku->sampul) }}" class="w-20 mb-2">
    @endif

    <input type="file" name="sampul" class="w-full mb-3 p-2 border rounded">

    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
</form>

@endsection