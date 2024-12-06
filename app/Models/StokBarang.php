<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';
    protected $fillable = [
        'id',
        'id_barang',
        'merk',
        'stok',
        'satuan',
        'kategori',
        'tanggal_exp'
    ];

    public function getRouteKeyName(): string
    {
        return 'id_barang'; // Gunakan kolom id_barang sebagai key untuk route
    }
    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
}
