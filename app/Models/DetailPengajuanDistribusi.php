<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengajuanDistribusi extends Model
{
    protected $table = 'detail_pengajuan_distribusi';
    protected $fillable = [
        'id',
        'id_pengajuan',
        'nama_barang',
        'jumlah_pengajuan',
        'satuan'
    ];

    public function pengajuanDistribusi()
    {
        return $this->belongsTo(PengajuanDistribusi::class, 'id_pengajuan', 'id');
    }

    public function StokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'id_barang', 'id');
    }
}
