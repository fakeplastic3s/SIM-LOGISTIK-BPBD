<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $fillable = [
        'id',
        'id_barang',
        'tanggal_masuk',
        'jumlah_masuk',
        'satuan',
        'sumber',

    ];
    public function StokBarang(): BelongsTo
    {
        return $this->belongsTo(StokBarang::class, 'id_barang', 'id');
    }


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

        // Event saat data baru dibuat
        static::creating(function ($stokBarang) {
            if ($stokBarang->StokBarang) {
                $stokBarang->StokBarang->stok += $stokBarang->jumlah_masuk;
                $stokBarang->StokBarang->save();
            }
        });

        // Event saat data di-update
        static::updating(function ($stokBarang) {
            $originalJumlahMasuk = $stokBarang->getOriginal('jumlah_masuk');
            $newJumlahMasuk = $stokBarang->jumlah_masuk;

            if ($stokBarang->StokBarang) {
                // Kurangi stok dengan jumlah lama
                $stokBarang->StokBarang->stok -= $originalJumlahMasuk;
                // Tambahkan stok dengan jumlah baru
                $stokBarang->StokBarang->stok += $newJumlahMasuk;
                $stokBarang->StokBarang->save();
            }
        });

        // Event saat data dihapus
        static::deleting(function ($stokBarang) {
            if ($stokBarang->StokBarang) {
                $stokBarang->StokBarang->stok -= $stokBarang->jumlah_masuk;
                $stokBarang->StokBarang->save();
            }
        });
    }
}
