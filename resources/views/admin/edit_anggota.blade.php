<form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="nama" value="{{ $anggota->nama }}" class="w-full mb-2 p-2 border">

    <input type="email" name="email" value="{{ $anggota->email }}" class="w-full mb-2 p-2 border">

    <select name="status" class="w-full mb-2 p-2 border">
        <option value="aktif" {{ $anggota->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ $anggota->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
        Update
    </button>
</form>