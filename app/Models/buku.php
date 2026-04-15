<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // Tambahkan baris ini!
    protected $table = 'buku'; 

    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit', 
        'isbn', 'kategori', 'stok', 'rak_lokasi', 'sampul'
    ];
}