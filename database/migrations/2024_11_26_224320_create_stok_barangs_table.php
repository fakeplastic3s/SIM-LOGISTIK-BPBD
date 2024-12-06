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
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->string('id', length: 25)->primary();
            $table->string('id_barang', length: 25);
            $table->foreign('id_barang')->references('id')->on('barang')->onUpdate('cascade')->onDelete('cascade');
            $table->string('merk', length: 50);
            $table->integer('stok')->nullable();
            $table->enum('satuan', ['pcs', 'kg', 'g', 'ml', 'liter', 'box'])->nullable();
            $table->string('kategori', length: 50);
            $table->date('tanggal_exp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barangs');
    }
};
