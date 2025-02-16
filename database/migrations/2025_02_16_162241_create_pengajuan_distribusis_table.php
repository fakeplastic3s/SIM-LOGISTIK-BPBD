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
        Schema::create('pengajuan_distribusi', function (Blueprint $table) {
            $table->string('id', length: 25)->primary();
            $table->date('tanggal_pengajuan');
            $table->string('nama_penerima', length: 25);
            $table->string('alamat_penerima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_distribusis');
    }
};
