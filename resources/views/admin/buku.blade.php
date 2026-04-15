@extends('layouts.admin')

@section('title', 'Kelola Buku')
@section('page_title', 'Manajemen Data Buku')

@section('content')
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-lg flex justify-between items-center">
            <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-white font-bold">&times;</button>
        </div>
    @endif

    <!-- TOTAL -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
            <p class="text-sm text-gray-500 uppercase font-bold text-[10px] tracking-wider">Total Koleksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $buku->count() }} Buku</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        
        <!-- HEADER + SEARCH -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- SEARCH -->
            <form method="GET" action="{{ route('admin.buku') }}" class="relative w-full md:w-96">
                <input 
                    type="text" 
                    name="search"
                    value="{{ $query ?? '' }}"
                    placeholder="Cari Judul, Penulis..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                >
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </form>

            <!-- BUTTON TAMBAH -->
            <button onclick="toggleModal('modal-tambah')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition shadow-lg shadow-indigo-200">
                <i class="fas fa-plus mr-2"></i> Tambah Buku Baru
            </button>
        </div>

        <!-- TABLE -->
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 uppercase text-[11px] font-bold text-gray-600 tracking-wider">
                <tr>
                    <th class="p-4 border-b">Sampul</th>
                    <th class="p-4 border-b">Informasi Buku</th>
                    <th class="p-4 border-b">Kategori</th>
                    <th class="p-4 border-b">Stok</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($buku as $b)
                <tr class="hover:bg-gray-50 transition">

                    <!-- SAMPUL -->
                    <td class="p-4">
                        @if($b->sampul)
                            <img src="{{ asset('sampul/'.$b->sampul) }}" class="w-10 h-14 object-cover rounded">
                        @else
                            <div class="w-10 h-14 bg-gray-200 rounded flex items-center justify-center text-[10px] text-gray-400 font-bold">
                                NO IMG
                            </div>
                        @endif
                    </td>

                    <!-- INFO -->
                    <td class="p-4">
                        <p class="font-bold text-indigo-900">{{ $b->judul }}</p>
                        <p class="text-sm text-gray-500 italic">{{ $b->penulis }} • {{ $b->tahun_terbit }}</p>
                    </td>

                    <!-- KATEGORI -->
                    <td class="p-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                            {{ $b->kategori }}
                        </span>
                    </td>

                    <!-- STOK -->
                    <td class="p-4 text-gray-700 font-medium">{{ $b->stok }}</td>

                    <!-- 🔥 AKSI (EDIT + DELETE) -->
                    <td class="p-4 text-center">
                        <div class="flex justify-center space-x-2">

                            <!-- EDIT -->
                            <a href="{{ route('admin.buku.edit', $b->id) }}" 
                               class="text-amber-500 hover:bg-amber-50 w-8 h-8 flex items-center justify-center rounded-full">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('admin.buku.delete', $b->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin hapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="text-red-500 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-full">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        Belum ada data buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            
            <div class="bg-indigo-900 p-4 text-white flex justify-between items-center">
                <h3 class="font-bold">
                    <i class="fas fa-book-medical mr-2"></i> Input Data Buku
                </h3>
                <button onclick="toggleModal('modal-tambah')" class="text-2xl">&times;</button>
            </div>

            <!-- FORM -->
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <input type="text" name="judul" placeholder="Judul Buku" required class="w-full border p-2 rounded">
                <input type="text" name="penulis" placeholder="Penulis" required class="w-full border p-2 rounded">

                <div class="grid grid-cols-2 gap-4">
                    <select name="kategori" class="w-full border p-2 rounded">
                        <option>Fiksi</option>
                        <option>Non-Fiksi</option>
                        <option>Sains</option>
                        <option>Teknologi</option>
                    </select>

                    <input type="number" name="tahun_terbit" placeholder="Tahun" required class="w-full border p-2 rounded">
                </div>

                <input type="number" name="stok" placeholder="Stok" required class="w-full border p-2 rounded">

                <!-- SAMPUL -->
                <div>
                    <label class="text-sm">Sampul Buku</label>
                    <input type="file" name="sampul" onchange="previewImage(event)" class="w-full border p-2 rounded">
                    <img id="preview" class="mt-2 w-20 hidden rounded">
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="toggleModal('modal-tambah')" class="flex-1 px-4 py-2 border rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function previewImage(event) {
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(event.target.files[0]);
            img.classList.remove('hidden');
        }
    </script>

@endsection