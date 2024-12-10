<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('cetak', [PDFController::class, 'cetakLaporan'])->name('stok-barang.print');
Route::get('cetak/{id}', [PDFController::class, 'cetakLaporanDistribusi'])->name('distribusi.print');
