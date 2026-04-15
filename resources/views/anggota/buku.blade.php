@extends('layouts.anggota')

@section('title', 'Daftar Buku')

@section('content')

@if(session('success'))
<div class="bg-green-500 text-white p-4 rounded-lg mb-6 flex justify-between items-center shadow-lg animate-bounce-short">
    <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
</div>
@endif

@if(session('error'))
<div class="bg-red-500 text-white p-4 rounded-lg mb-6 flex justify-between items-center shadow-lg">
    <span><i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
</div>
@endif

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Koleksi Buku</h1>
    <p class="text-gray-500 text-sm mt-1">Pilih buku yang ingin kamu pinjam hari ini.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="relative">
        <input type="text" id="searchInput" onkeyup="filterBuku()"
            placeholder="Cari judul buku atau nama penulis..."
            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm transition-all">
        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
    </div>
</div>

<div id="bukuGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($buku as $b)
    <div class="buku-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
        data-judul="{{ strtolower($b->judul) }}" data-penulis="{{ strtolower($b->penulis) }}">

        <div class="h-52 bg-indigo-50 flex items-center justify-center overflow-hidden relative group">
            @if($b->sampul)
                <img src="{{ asset('sampul/'.$b->sampul) }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
            @else
                <i class="fas fa-book text-indigo-200 text-6xl"></i>
            @endif
            
            <div class="absolute top-2 right-2">
                <span class="text-[10px] bg-white/90 backdrop-blur px-2 py-1 rounded-lg font-bold text-indigo-600 shadow-sm uppercase">
                    {{ $b->kategori ?? 'Umum' }}
                </span>
            </div>
        </div>

        <div class="p-5">
            <h3 class="font-bold text-gray-800 text-sm leading-tight h-10 line-clamp-2 uppercase tracking-tight">{{ $b->judul }}</h3>
            <p class="text-xs text-gray-400 mt-2"><i class="fas fa-pen-nib mr-1"></i> {{ $b->penulis }}</p>

            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Stok</p>
                    <p class="text-sm font-bold {{ $b->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $b->stok }} Buku
                    </p>
                </div>

                @if($b->stok > 0)
                <button
                    onclick="confirmPinjam('{{ $b->id }}', '{{ e($b->judul) }}')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-4 py-2 rounded-lg transition-colors font-bold shadow-md shadow-indigo-100">
                    PINJAM
                </button>
                @else
                <button disabled class="bg-gray-100 text-gray-400 text-xs px-4 py-2 rounded-lg cursor-not-allowed font-bold">
                    HABIS
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
        <i class="fas fa-book-open text-5xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-medium">Ups! Koleksi buku belum tersedia.</p>
    </div>
    @endforelse
</div>

<div id="modalPinjam" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100">
        <div class="bg-indigo-600 p-6 text-white text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-paper-plane text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold">Konfirmasi Pinjaman</h3>
        </div>
        
        <div class="p-8">
            <p class="text-gray-500 text-center text-sm mb-1">Anda akan mengajukan peminjaman buku:</p>
            <p class="font-extrabold text-gray-800 text-center text-lg mt-2 px-2" id="judulBukuText"></p>

            <form method="POST" action="{{ route('pinjam.store') }}" class="mt-8">
                @csrf
                <input type="hidden" name="buku_id" id="inputBukuId">
                
                <div class="flex flex-col gap-3">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        YA, AJUKAN SEKARANG
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="w-full bg-gray-50 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-100 transition-all">
                        BATAL
                    </button>
                </div>
            </form>
            <p class="text-[10px] text-center text-gray-400 mt-6 italic">
                *Peminjaman memerlukan persetujuan admin sebelum buku dapat diambil.
            </p>
        </div>
    </div>
</div>

<script>
    // FUNGSI CARI BUKU
    function filterBuku() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.buku-card');
        
        cards.forEach(card => {
            const judul = card.dataset.judul;
            const penulis = card.dataset.penulis;
            
            if (judul.includes(query) || penulis.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // FUNGSI MODAL
    function confirmPinjam(id, judul) {
        const modal = document.getElementById('modalPinjam');
        document.getElementById('inputBukuId').value = id;
        document.getElementById('judulBukuText').innerText = judul;
        
        // Munculkan modal (Hapus class hidden)
        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('modalPinjam');
        // Sembunyikan modal (Tambah class hidden)
        modal.classList.add('hidden');
    }

    // Menutup modal jika klik area luar modal
    window.onclick = function(event) {
        const modal = document.getElementById('modalPinjam');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

@endsection