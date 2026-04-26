<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('buku', function (Blueprint $table) {
        $table->string('sampul')->nullable();
    });
}

    
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            //
        });
    }
};
