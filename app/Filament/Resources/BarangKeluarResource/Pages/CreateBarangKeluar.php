<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Actions;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangKeluar extends CreateRecord
{
    protected static string $resource = BarangKeluarResource::class;
    protected static ?string $title = 'Tambah Data Barang Keluar';

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
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }

    // Hapus error validasi pada field yang diperbarui
    public function updated($propertyName)
    {
        $this->resetValidation($propertyName); // Hapus error validasi
    }
}
