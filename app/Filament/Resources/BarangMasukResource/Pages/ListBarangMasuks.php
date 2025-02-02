<?php

namespace App\Filament\Resources\BarangMasukResource\Pages;

use App\Filament\Resources\BarangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangMasuks extends ListRecords
{
    protected static string $resource = BarangMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-s-plus-circle')
                ->visible(fn() => request()->user()->name === 'Admin Logistik'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Data Barang Masuk';
    }
}
