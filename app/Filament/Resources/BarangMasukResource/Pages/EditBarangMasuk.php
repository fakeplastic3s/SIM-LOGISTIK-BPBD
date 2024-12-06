<?php

namespace App\Filament\Resources\BarangMasukResource\Pages;

use App\Filament\Resources\BarangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangMasuk extends EditRecord
{
    protected static string $resource = BarangMasukResource::class;
    protected static ?string $title = 'Edit Data Barang Masuk';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('back_to_list')
                ->icon('heroicon-m-arrow-uturn-left')
                ->label('Kembali')
                ->url(url()->previous())
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
