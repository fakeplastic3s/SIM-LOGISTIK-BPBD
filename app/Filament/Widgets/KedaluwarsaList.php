<?php

namespace App\Filament\Widgets;

use App\Models\StokBarang;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KedaluwarsaList extends BaseWidget
{
    protected static ?string $heading = 'List Barang Hampir Kedaluwarsa Dan Kedaluwarsa';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Tidak Ada Barang Hampir Kedaluwarsa Dan Kedaluwarsa')
            ->query(
                StokBarang::query()
                    ->where('kategori', 'Barang Kedaluwarsa')
                    ->where('tanggal_exp', '<=', now()->addDays(30))
                    ->where('stok', '>', 0)
                    ->orderBy('tanggal_exp', 'asc')
            )
            ->columns([
                TextColumn::make('merk'),
                TextColumn::make('stok'),
                TextColumn::make('satuan'),
                TextColumn::make('tanggal_exp')
                    ->date('l, d F Y')
                    ->badge()
                    ->color(function ($record) {
                        $expDate = \Carbon\Carbon::parse($record->tanggal_exp)->startOfDay(); // Mengatur waktu menjadi 00:00
                        $now = \Carbon\Carbon::now()->startOfDay(); // Mengatur waktu saat ini menjadi 00:00
                        $daysRemaining = $now->diffInDays($expDate, false); // Menghitung selisih hari dengan tanggal kedaluwarsa

                        // Cek apakah tanggal kedaluwarsa sudah lewat
                        if ($expDate->isPast() || $daysRemaining == 0) {
                            return 'danger'; // Merah jika sudah kedaluwarsa
                        }

                        // Cek jika kedaluwarsa dalam waktu 30 hari atau kurang
                        if ($daysRemaining <= 30 && $daysRemaining > 0) {
                            return 'warning'; // Kuning jika dalam 30 hari atau kurang
                        }

                        // Jika kedaluwarsa lebih dari 30 hari
                        return 'success';
                    })
                // ->format(function ($value) {
                //     return $value->format('d M Y');
                // }),
            ]);
    }
}
