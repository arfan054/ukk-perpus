@extends('layouts.admin')

@section('title', 'Data Anggota')
@section('page_title', 'Manajemen Anggota')

@section('content')

<div class="bg-white rounded-xl shadow-md overflow-hidden">

    <!-- HEADER -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">

        <!-- SEARCH -->
        <div class="relative w-full md:w-96">
            <input type="text" placeholder="Cari Nama Anggota..."
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>

        <!-- BUTTON TAMBAH -->
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-user-plus mr-2"></i> Tambah Anggota
        </button>
    </div>

    <!-- TABLE -->
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 border-b">Profil</th>
                <th class="p-4 border-b">Kontak</th>
                <th class="p-4 border-b">Status</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($anggota as $a)
            <tr class="hover:bg-gray-50 transition">

                <!-- PROFIL -->
                <td class="p-4 border-b">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($a->nama) }}"
                            class="w-10 h-10 rounded-full">

                        <div>
                            <p class="font-bold text-gray-800">{{ $a->nama }}</p>
                            <p class="text-xs text-gray-500">
                                Bergabung: {{ $a->created_at ? $a->created_at->format('M Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </td>

                <!-- KONTAK -->
                <td class="p-4 border-b">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $a->email }}
                    </p>
                </td>

                <!-- STATUS -->
                <td class="p-4 border-b">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $a->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($a->status) }}
                    </span>
                </td>

                <!-- AKSI -->
                <td class="p-4 border-b">
                    <div class="flex space-x-2">

                        <!-- EDIT -->
                        <a href="{{ route('admin.anggota.edit', $a->id) }}"
                            class="text-yellow-500 p-2">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST"
                              onsubmit="return confirm('Yakin mau hapus anggota ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-500 p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center p-6 text-gray-500">
                    Belum ada data anggota
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH ANGGOTA -->
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white rounded-lg p-6 w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Tambah Anggota</h2>

        <form action="{{ route('admin.anggota.store') }}" method="POST">
            @csrf

            <input type="text" name="nama" placeholder="Nama"
                class="w-full mb-3 p-2 border rounded" required>

            <input type="email" name="email" placeholder="Email"
                class="w-full mb-3 p-2 border rounded" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full mb-3 p-2 border rounded" required>

            <select name="status" class="w-full mb-3 p-2 border rounded">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>

            <div class="flex justify-end gap-2">

                <button type="button"
                    onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-400 text-white rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Simpan
                </button>

            </div>
        </form>

    </div>
</div>

@endsection