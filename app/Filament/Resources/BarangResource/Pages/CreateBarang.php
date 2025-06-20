<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;
    protected static ?string $title = 'Tambah Data Barang';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Data berhasil disimpan'; // Ubah pesan toast
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah Data';
    }

    protected function getFormActions(): array
    {
        return [
            // Simpan
            \Filament\Pages\Actions\ButtonAction::make('simpan')
                ->label('Simpan')
                ->action(fn() => $this->create())
                // ->submit('submit') // Tetap menjalankan fungsi submit bawaan
                ->color('primary') // Warna tombol
                ->icon('heroicon-o-plus-circle')
            // ->requiresConfirmation() // Tampilkan konfirmasi sebelum submit (opsional)
            // ->withSpinner()
            , // Ikon tombol (opsional)

            // Tombol BatalButtonAction
            \Filament\Pages\Actions\ButtonAction::make('batal')
                ->label('Batal') // Label tombol
                ->url($this->getResource()::getUrl('index')) // Redirect ke halaman index
                ->color('gray') // Warna tombol
                ->icon('heroicon-o-x-circle'),
        ];
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName); // Hapus error validasi pada field yang diperbarui
    }
}
