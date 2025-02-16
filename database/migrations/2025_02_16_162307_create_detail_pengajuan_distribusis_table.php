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
        Schema::create('detail_pengajuan_distribusi', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan', length: 25);
            $table->foreign('id_pengajuan')->references('id')->on('pengajuan_distribusi')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_barang', length: 25);
            $table->integer('jumlah_pengajuan');
            $table->enum('satuan', ['pcs', 'kg', 'g', 'ml', 'liter', 'box']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan_distribusi');
    }
};
