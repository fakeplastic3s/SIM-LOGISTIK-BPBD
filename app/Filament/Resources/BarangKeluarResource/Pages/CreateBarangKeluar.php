<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use Filament\Actions;
use App\Models\StokBarang;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BarangKeluarResource;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;

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
                ->color('gray')
                ->icon('heroicon-o-x-circle'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Data';
    }

    // Hapus error validasi pada field yang diperbarui
    public function updated($propertyName, $value)
    {
        $this->resetValidation($propertyName); // Hapus error validasi

    }
}
