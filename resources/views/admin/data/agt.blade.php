@extends('layouts.admin')

@section('title', 'Data Anggota')
@section('page_title', 'Manajemen Anggota')

@section('content')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
    + Tambah Anggota
</button>
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('anggota.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>ID Anggota</label>
                        <input type="text" name="id_anggota" class="form-control" placeholder="LIB-2026-xxx" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<tbody>
    @foreach($data_anggota as $agt)
    <tr>
        <td>{{ $agt->nama }}</td>
        <td>{{ $agt->id_anggota }}</td>
        <td>{{ $agt->email }}</td>
        <td><span class="badge bg-success">{{ $agt->status }}</span></td>
        <td>
            </td>
    </tr>
    @endforeach
</tbody>