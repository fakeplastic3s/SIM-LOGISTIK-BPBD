<?php

namespace App\Filament\Resources\PengajuanDistribusiResource\Pages;

use App\Filament\Resources\PengajuanDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailPengajuan extends ViewRecord
{
    protected static string $resource = PengajuanDistribusiResource::class;

    protected static ?string $title = 'Detail Pengajuan Distribusi';

    public static function getViewTitle(): string
    {
        return 'Detail Pengajuan Distribusi';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_list')
                ->icon('heroicon-m-arrow-uturn-left')
                ->label('Kembali')
                ->url(url('admin/pengajuan-distribusis'))
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Detail Data';
    }
}
