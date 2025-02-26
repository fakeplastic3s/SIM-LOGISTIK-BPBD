<?php

namespace App\Filament\Resources\PengajuanDistribusiResource\Pages;

use App\Filament\Resources\PengajuanDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanDistribusis extends ListRecords
{
    protected static string $resource = PengajuanDistribusiResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-s-plus-circle')
                ->visible(fn() => \Auth::user()->role === 'pusdalops'),
        ];
    }
    public function getBreadcrumb(): string
    {
        return 'Data Pengajuan Distribusi Barang';
    }
}
