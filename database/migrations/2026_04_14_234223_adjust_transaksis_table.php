<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        // Mengubah tanggal_pinjam jadi nullable dan set default status
        $table->date('tanggal_pinjam')->nullable()->change();
        $table->enum('status', ['menunggu', 'dipinjam', 'kembali', 'ditolak'])->default('menunggu')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            //
        });
    }
};
