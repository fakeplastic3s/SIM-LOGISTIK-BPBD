<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangKeluars extends ListRecords
{
    protected static string $resource = BarangKeluarResource::class;


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
        return 'Data Distribusi Barang';
    }
}
