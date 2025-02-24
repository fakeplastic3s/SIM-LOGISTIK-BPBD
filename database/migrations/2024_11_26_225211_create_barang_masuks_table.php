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
            $table->string('merk', length: 25);
            $table->date('tanggal_masuk');
            $table->date('tanggal_exp')->nullable();
            $table->integer('jumlah_masuk');
            $table->enum('satuan', ['pcs', 'kg', 'g', 'ml', 'liter', 'box']);
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
