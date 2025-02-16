<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUsers extends CreateRecord
{
    protected static ?string $title = 'Tambah Data Penggun';
    protected static string $resource = UsersResource::class;
    public function getBreadcrumb(): string
    {
        return 'Tambah Data';
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
}
