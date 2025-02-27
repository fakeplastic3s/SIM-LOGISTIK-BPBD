<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $fillable = [
        'id',
        'merk',
        'tanggal_masuk',
        'tanggal_exp',
        'jumlah_masuk',
        'satuan',
        'sumber',

    ];


    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    // Boot method untuk menambahkan event
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barangMasuk) {
            $stokBarang = StokBarang::where('merk', $barangMasuk->merk)
                ->where('tanggal_exp', null)
                ->first();

            $kedaluwarsa = StokBarang::where('merk', $barangMasuk->merk)
                ->where('tanggal_exp', $barangMasuk->tanggal_exp)
                ->first();

            $barang = StokBarang::where('merk', $barangMasuk->merk)
                ->first();


            // dd($barang);
            if ($stokBarang) {
                $stokBarang->stok += $barangMasuk->jumlah_masuk;
                $stokBarang->tanggal_exp = $barangMasuk->tanggal_exp;
                $stokBarang->save();
            } else if ($kedaluwarsa) {
                $kedaluwarsa->stok += $barangMasuk->jumlah_masuk;
                $kedaluwarsa->save();
            } else {
                $id_barang = 'BRG-' . Carbon::now()->format('dmhis');
                $barangMasuk->id_barang = $id_barang;
                StokBarang::create([
                    'id' => 'BRG-' . $id_barang,
                    'id_barang' => $barang->id_barang,
                    'merk' => $barang->merk,
                    'tanggal_exp' => $barangMasuk->tanggal_exp,
                    'stok' => $barangMasuk->jumlah_masuk,
                    'satuan' => $barangMasuk->satuan,
                    'kategori' => $barang->kategori
                ]);
            }
        });

        // Event saat data diubah
        static::updating(function ($barangMasuk) {
            $original = $barangMasuk->getOriginal();
            $stokBarang = StokBarang::where('merk', $barangMasuk->merk)
                ->where('tanggal_exp', $original['tanggal_exp'])
                ->first();

            if ($stokBarang) {
                // Kurangi stok dengan jumlah lama
                $stokBarang->stok -= $original['jumlah_masuk'];
                // Tambahkan stok dengan jumlah baru
                $stokBarang->stok += $barangMasuk->jumlah_masuk;
                $stokBarang->tanggal_exp = $barangMasuk->tanggal_exp;
                $stokBarang->save();
            }
        });
        // Event saat data di-update
        // static::updating(function ($stokBarang) {
        //     $originalJumlahMasuk = $stokBarang->getOriginal('jumlah_masuk');
        //     $newJumlahMasuk = $stokBarang->jumlah_masuk;

        //     if ($stokBarang->StokBarang) {
        //         // Kurangi stok dengan jumlah lama
        //         $stokBarang->StokBarang->stok -= $originalJumlahMasuk;
        //         // Tambahkan stok dengan jumlah baru
        //         $stokBarang->StokBarang->stok += $newJumlahMasuk;
        //         $stokBarang->StokBarang->save();
        //     }
        // });

        // Event saat data dihapus
        static::deleting(function ($barangMasuk) {
            $stokBarang = StokBarang::where('merk', $barangMasuk->merk)
                ->where('tanggal_exp', $barangMasuk->tanggal_exp)
                ->first();

            // dd($stokBarang);
            if ($stokBarang) {
                $stokBarang->stok -= $barangMasuk->jumlah_masuk;
                $stokBarang->save();
            }
        });
        // static::deleting(function ($stokBarang) {
        //     if ($stokBarang->StokBarang) {
        //         $stokBarang->StokBarang->stok -= $stokBarang->jumlah_masuk;
        //         $stokBarang->StokBarang->save();
        //     }
        // });
    }
}
