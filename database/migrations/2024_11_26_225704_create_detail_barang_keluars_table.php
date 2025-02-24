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
        Schema::create('detail_barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang_keluar', length: 25);
            $table->foreign('id_barang_keluar')->references('id')->on('barang_keluar')->onUpdate('cascade')->onDelete('cascade');
            $table->string('id_barang', length: 25);
            $table->foreign('id_barang')->references('id')->on('stok_barang')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah_keluar');
            $table->enum('satuan', ['pcs', 'kg', 'g', 'ml', 'liter', 'box']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_keluars');
    }
};
