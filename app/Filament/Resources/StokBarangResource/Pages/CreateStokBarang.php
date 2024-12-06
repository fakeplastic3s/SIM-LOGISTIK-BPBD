<?php

namespace App\Filament\Resources\StokBarangResource\Pages;

use App\Filament\Resources\StokBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStokBarang extends CreateRecord
{
    protected static string $resource = StokBarangResource::class;

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName); // Hapus error validasi pada field yang diperbarui
    }
}
