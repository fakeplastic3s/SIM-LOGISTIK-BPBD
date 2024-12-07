<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function cetakLaporan()
    {
        // Ambil semua data stok barang
        $barang = StokBarang::with('barang')->orderBy('tanggal_exp', 'asc')->where('stok', '>', 0)->get();
        $data = [
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'barang' => $barang
        ];
        // dd($data);

        // Generate PDF menggunakan view
        $pdf = PDF::loadView('stokPDF', compact('data'));

        // Unduh file PDF
        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-stok-barang-' . now()->format('Ymd') . '.pdf'
        );
    }
}
