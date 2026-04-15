<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    // Nama tabel di database (pastikan sama dengan migration)
    protected $table = 'anggotas';

 protected $fillable = [
    'nama',
    'email',
    'status',
    'user_id' // 🔥 WAJIB ADA
];
}