<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        
        Schema::table('transaksis', function (Blueprint $table) {
            $table->date('tanggal_pinjam')->nullable()->change();
        });

        // 2. Gunakan SQL mentah untuk menangani rewelnya PostgreSQL
        // Kita hapus dulu gemboknya (constraint) kalau sudah terlanjur ada untuk mencegah error Duplicate Object
        DB::statement('ALTER TABLE transaksis DROP CONSTRAINT IF EXISTS transaksis_status_check');

        // Pastikan tipe data kolomnya benar
        DB::statement('ALTER TABLE transaksis ALTER COLUMN status TYPE VARCHAR(255)');
        
        // Pasang gembok (constraint) baru
        DB::statement("ALTER TABLE transaksis ADD CONSTRAINT transaksis_status_check CHECK (status IN ('menunggu', 'dipinjam', 'kembali', 'ditolak'))");
        
        // Set nilai default
        DB::statement("ALTER TABLE transaksis ALTER COLUMN status SET DEFAULT 'menunggu'");
    }

    
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->date('tanggal_pinjam')->nullable(false)->change();
        });

        // Hapus constraint saat rollback
        DB::statement('ALTER TABLE transaksis DROP CONSTRAINT IF EXISTS transaksis_status_check');
    }
};