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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->string('id', length: 25)->primary();
            $table->string('id_barang', length: 25);
            $table->foreign('id_barang')->references('id')->on('stok_barang')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->integer('jumlah_masuk');
            $table->enum('satuan', ['pcs', 'kg', 'gr', 'ml', 'liter', 'box']);
            $table->enum('sumber', ['APBD Provinsi Jawa Tengah', 'APBD Kabupaten Pekalongan', 'Masyarakat']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
