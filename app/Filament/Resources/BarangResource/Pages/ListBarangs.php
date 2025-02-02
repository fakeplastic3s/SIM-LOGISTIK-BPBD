<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-s-plus-circle')
                ->visible(fn() => request()->user()->name === 'Admin Logistik')
        ];
    }
    public function getBreadcrumb(): string
    {
        return 'Data Barang';
    }
}
