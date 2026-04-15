<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        // Mengubah tanggal_pinjam tetap bisa pakai cara biasa
        $table->date('tanggal_pinjam')->nullable()->change();
    });

    // Khusus untuk 'status', kita gunakan SQL manual agar Postgres tidak bingung
    DB::statement('ALTER TABLE transaksis ALTER COLUMN status TYPE VARCHAR(255)');
    DB::statement("ALTER TABLE transaksis ADD CONSTRAINT transaksis_status_check CHECK (status IN ('menunggu', 'dipinjam', 'kembali', 'ditolak'))");
    DB::statement("ALTER TABLE transaksis ALTER COLUMN status SET DEFAULT 'menunggu'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Untuk rollback, hapus constraint-nya saja
            DB::statement('ALTER TABLE transaksis DROP CONSTRAINT IF EXISTS transaksis_status_check');
        });
    }
};