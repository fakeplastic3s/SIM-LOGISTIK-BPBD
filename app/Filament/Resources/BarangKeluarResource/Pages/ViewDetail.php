<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDetail extends ViewRecord
{
    protected static string $resource = BarangKeluarResource::class;
    protected static ?string $title = 'Detail Distribusi Barang';

    public static function getViewTitle(): string
    {
        return 'Detail Barang Keluar';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_list')
                ->icon('heroicon-m-arrow-uturn-left')
                ->label('Kembali')
                ->url(url('admin/barang-keluars'))
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Detail Data';
    }
}
