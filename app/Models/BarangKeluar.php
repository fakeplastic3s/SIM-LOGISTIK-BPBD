<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
    protected $table = 'barang_keluar';
    protected $fillable = [
        'id',
        'tanggal_distribusi',
        'nama_penerima',
        'alamat_penerima',
        'status',
        'foto',


    ];

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }
    public function detailBarangKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'id_barang_keluar', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Event saat data dihapus
        static::deleting(function ($barangKeluar) {

            $detailBarang = \App\Models\DetailBarangKeluar::where('id_barang_keluar', $barangKeluar->id)
                ->with('stokBarang') // Eager load relasi stok barang
                ->get();

            $detailBarang->each(function ($barangKeluar) {
                $barang = $barangKeluar->stokBarang;
                if ($barang) {
                    $barang->increment('stok', $barangKeluar->jumlah_keluar); // Gunakan increment untuk update langsung
                }
            });
        });
    }
}
