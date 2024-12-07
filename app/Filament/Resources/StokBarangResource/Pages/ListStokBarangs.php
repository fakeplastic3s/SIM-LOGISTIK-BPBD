<?php

namespace App\Filament\Resources\StokBarangResource\Pages;

use App\Filament\Resources\StokBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ButtonAction;

class ListStokBarangs extends ListRecords
{
    protected static string $resource = StokBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_laporan')
                ->label('Cetak Laporan')
                ->icon('heroicon-s-printer')
                ->color('gray')
                ->url(fn() => route('stok-barang.print'))
                ->openUrlInNewTab(),
        ];
    }
}
