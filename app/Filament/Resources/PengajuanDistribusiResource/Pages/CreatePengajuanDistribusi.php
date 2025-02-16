<?php

namespace App\Filament\Resources\PengajuanDistribusiResource\Pages;

use App\Filament\Resources\PengajuanDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengajuanDistribusi extends CreateRecord
{
    protected static string $resource = PengajuanDistribusiResource::class;

    protected static ?string $title = 'Tambah Data Pengajuan Distribusi Barang';

    // Redirect ke halaman index
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Pesan Toast
    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Data berhasil disimpan';
    }

    // Button Simpan dan Batal
    protected function getFormActions(): array
    {
        return [
            // Simpan
            \Filament\Pages\Actions\ButtonAction::make('simpan')
                ->label('Simpan')
                ->action(fn() => $this->create())
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
            // ->requiresConfirmation() // Tampilkan konfirmasi sebelum submit 
            ,

            // Tombol BatalButtonAction
            \Filament\Pages\Actions\ButtonAction::make('batal')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index')) // Redirect ke halaman index
                ->color('gray')
                ->icon('heroicon-o-x-circle'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Data';
    }
}
