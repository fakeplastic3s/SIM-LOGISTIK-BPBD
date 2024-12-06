<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailBarang extends ViewRecord
{
    protected static string $resource = BarangResource::class;
    protected static ?string $title = 'Detail Barang';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_list')
                ->icon('heroicon-m-arrow-uturn-left')
                ->label('Kembali')
                ->url(url()->previous())
        ];
    }
}
