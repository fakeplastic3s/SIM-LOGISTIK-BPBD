<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangKeluar extends EditRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-m-trash'),
            Actions\ViewAction::make()
                ->icon('heroicon-m-list-bullet')
                ->label('Detail'),
            Actions\Action::make('back_to_list')
                ->icon('heroicon-m-arrow-uturn-left')
                ->label('Kembali')
                ->url('/admin/barang-keluars')
        ];
    }
}
