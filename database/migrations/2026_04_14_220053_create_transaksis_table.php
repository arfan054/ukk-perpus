<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    // 🔥 FIX MANUAL FOREIGN KEY
    $table->unsignedBigInteger('buku_id');
    $table->foreign('buku_id')
          ->references('id')
          ->on('buku')
          ->onDelete('cascade');

    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali')->nullable();

    $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};