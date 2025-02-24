<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    protected $table = 'detail_barang_keluar';
    protected $fillable = [
        'id_barang_keluar',
        'id_barang',
        'jumlah_keluar',
        'satuan'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'id_barang_keluar', 'id');
    }

    public function StokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'id_barang', 'id');
    }

    public function getRouteKeyName(): string
    {
        return 'id_barang'; // Gunakan kolom id_barang sebagai key untuk route
    }
    public function getKeyType()
    {
        return 'string';
    }


    protected static function boot()
    {
        parent::boot();

        // Event saat data ditambahkan
        static::creating(function ($detailBarangKeluar) {
            $barang = \App\Models\StokBarang::find($detailBarangKeluar->id_barang);
            if ($barang) {
                // Validasi stok barang
                if ($barang->stok < $detailBarangKeluar->jumlah_keluar) {
                    throw new \Exception("Stok barang tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                // Kurangi stok barang
                $barang->stok -= $detailBarangKeluar->jumlah_keluar;
                $barang->save();
            }
        });

        // Event saat data diperbarui
        static::updating(function ($detailBarangKeluar) {
            $original = $detailBarangKeluar->getOriginal();
            // Hitung selisih jumlah sebelum dan sesudah update
            $quantityDifference = $detailBarangKeluar->jumlah_keluar - $original['jumlah_keluar'];
            $barang = \App\Models\StokBarang::find($detailBarangKeluar->id_barang);
            if ($barang) {
                // Validasi stok jika jumlah barang keluar bertambah
                if ($quantityDifference > 0 && $barang->stok < $quantityDifference) {
                    throw new \Exception("Stok barang tidak mencukupi untuk perubahan ini. Stok tersedia: {$barang->stok}");
                }

                // Sesuaikan stok barang
                $barang->stok -= $quantityDifference;
                $barang->save();
            }
        });

        // Event saat data dihapus
        static::deleting(function ($detailBarangKeluar) {
            $barang = \App\Models\StokBarang::find($detailBarangKeluar->id_barang);
            if ($barang) {
                // Kembalikan stok barang
                $barang->stok += $detailBarangKeluar->jumlah_keluar;
                $barang->save();
            }
        });
    }
}
