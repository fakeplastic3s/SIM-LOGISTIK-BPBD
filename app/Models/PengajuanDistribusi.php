<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDistribusi extends Model
{
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
    protected $table = 'pengajuan_distribusi';
    protected $fillable = [
        'id',
        'tanggal_pengajuan',
        'nama_penerima',
        'alamat_penerima',
    ];

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }
    public function detailPengajuan()
    {
        return $this->hasMany(DetailPengajuanDistribusi::class, 'id_pengajuan', 'id');
    }
}
