<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BarangKeluarResource;

class ListBarangKeluars extends ListRecords
{
    protected static string $resource = BarangKeluarResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-s-plus-circle')
                ->visible(fn() => Auth::user()->role === 'admin'),
        ];
    }
    public function getBreadcrumb(): string
    {
        return 'Data Distribusi Barang';
    }
}
