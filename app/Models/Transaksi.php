<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis'; // Pastikan nama tabelnya benar

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali', // <--- CEK: Apakah ini sudah ditulis?
        'status',
    ];

    // Tambahkan ini supaya Laravel otomatis mengenali kolom sebagai Tanggal
    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function buku() {
        return $this->belongsTo(Buku::class);
    }
}