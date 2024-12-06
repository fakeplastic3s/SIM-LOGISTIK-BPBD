<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = [
        'id',
        'nama_barang',



    ];

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }
    public function StokBarang()
    {
        return $this->hasMany(StokBarang::class, 'id_barang', 'id');
    }
}
