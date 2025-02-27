<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\StokBarang;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StokBarangResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\StokBarangResource\RelationManagers;



class StokBarangResource extends Resource
{
    protected static ?string $model = StokBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function canViewAny(): bool
    {

        return \Auth::user()->role === 'admin' || \Auth::user()->role === 'kepala';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(StokBarang::query()->orderBy('tanggal_exp', 'asc')->where('stok', '>', 0))
            ->columns([
                TextColumn::make('id')
                    ->label('ID Stok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        return $record->stok . ' ' . $record->satuan;
                    }),
                // TextColumn::make('satuan')
                //     ->label('Satuan')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('stok')
                //     ->label('Detail Barang')
                //     ->limit(15)
                //     ->formatStateUsing(function ($record) {
                //         // dd($record->detailBarangKeluar);
                //         return $record->StokBarang
                //             ->map(fn($detail) => "{$detail->merk} ({$detail->stok} {$detail->satuan})")
                //             ->implode(', ');
                //     }),
                // TextColumn::make('StokBarang')
                //     ->label('Total Stok')
                //     ->formatStateUsing(function ($record) {
                //         return $record->StokBarang->sum('stok'); // Hitung total stok
                //     }),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pangan' => 'primary',
                        'Sandang' => 'info',
                    }),
                TextColumn::make('tanggal_exp')
                    ->label('Kedaluwarsa')
                    ->sortable()
                    ->date('l, d F Y')
                    ->badge()
                    ->color(function ($record) {
                        $expDate = \Carbon\Carbon::parse($record->tanggal_exp)->startOfDay(); // Mengatur waktu menjadi 00:00
                        $now = \Carbon\Carbon::now()->startOfDay(); // Mengatur waktu saat ini menjadi 00:00
                        $daysRemaining = $now->diffInDays($expDate, false); // Menghitung selisih hari dengan tanggal kedaluwarsa

                        if ($expDate->isPast() || $daysRemaining == 0) {
                            $color = 'danger'; // Merah jika sudah kedaluwarsa
                        } elseif ($daysRemaining <= 30 && $daysRemaining > 0) {
                            $color = 'warning'; // Kuning jika hampir kedaluwarsa
                        } else {
                            $color = 'success'; // Hijau jika masih lama
                        }
                        return $color;

                        // // Cek apakah tanggal kedaluwarsa sudah lewat
                        // if ($expDate->isPast() || $daysRemaining == 0) {
                        //     return 'danger'; // Merah jika sudah kedaluwarsa
                        // }

                        // // Cek jika kedaluwarsa dalam waktu 30 hari atau kurang
                        // if ($daysRemaining <= 30 && $daysRemaining > 0) {
                        //     return 'warning'; // Kuning jika dalam 30 hari atau kurang
                        // }

                        // // Jika kedaluwarsa lebih dari 30 hari
                        // return 'success';
                    })
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options([
                        'Sandang' => 'Sandang',
                        'Pangan' => 'Pangan',
                    ])
                    ->label('Kategori Barang'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                // BulkAction::make('cetak_pilih')
                //     ->label('Cetak Laporan')
                //     ->icon('heroicon-s-printer')
                //     ->color('info')
                //     ->url(function ($records) {
                //         $ids = $records->pluck('id')->join(',');
                //         return route('stok-barang.pdf', ['ids' => $ids]);
                //     })
                //     ->openUrlInNewTab(),
                ExportBulkAction::make()->exports([
                    // setting nama file export
                    ExcelExport::make()->fromTable()->withFilename('Data Logistik-' . date('d F Y His')),

                ])
                    ->label('Export Excel'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStokBarangs::route('/'),
            // 'create' => Pages\CreateStokBarang::route('/create'),
            // 'edit' => Pages\EditStokBarang::route('/{record}/edit'),
        ];
    }
}
