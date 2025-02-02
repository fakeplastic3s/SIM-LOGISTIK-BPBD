<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Filament\Resources\BarangResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\StokBarang;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $modelLabel = 'Barang';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('id')
                            ->id('id')
                            ->columnSpan(2)
                            ->default(fn() => 'BRG-' . strtoupper(\Str::random(5))) // Generate random string
                            ->readOnly()
                            ->label('ID Barang')
                            ->required(),
                        TextInput::make('nama_barang')
                            ->columnSpan(2)
                            ->label('Nama Barang')
                            ->required()
                            ->reactive()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Nama barang sudah ada.',
                                'required' => 'Nama barang tidak boleh kosong.',
                            ]),
                        TableRepeater::make('StokBarang')
                            ->relationship('StokBarang')
                            ->label('Barang')
                            ->headers([
                                Header::make('ID')->align(Alignment::Center),
                                Header::make('Merk')->align(Alignment::Center)->markAsRequired(),
                                Header::make('Stok')->align(Alignment::Center)->markAsRequired(),
                                Header::make('Satuan')->align(Alignment::Center)->markAsRequired(),
                                Header::make('Kategori')->align(Alignment::Center)->markAsRequired(),
                                Header::make(' ')->align(Alignment::Center),
                            ])
                            ->schema([
                                // dd(StokBarang::query()),
                                TextInput::make('id')
                                    ->id('id')
                                    ->columnSpan(2)
                                    ->default(fn() => 'BRG-' . Carbon::now()->format('dmhis')) // Generate random string
                                    ->readOnly()
                                    ->label('ID Barang')
                                    // ->visible(false)
                                    ->required(),
                                TextInput::make('merk')
                                    ->label('Merk')
                                    ->required()
                                    ->reactive()
                                    ->validationMessages([
                                        'required' => 'Merk tidak boleh kosong.',
                                    ]),
                                TextInput::make('stok')
                                    ->label('Stok')
                                    ->required()
                                    ->readOnly()
                                    ->default(0)
                                    ->reactive()
                                    ->numeric(),
                                Select::make('satuan')
                                    ->label('Satuan')
                                    ->required()
                                    ->options([
                                        'pcs' => 'pcs',
                                        'kg' => 'Kilogram',
                                        'g' => 'Gram',
                                        'ml' => 'Mililiter',
                                        'liter' => 'Liter',
                                        'box' => 'Box',
                                    ])
                                    ->validationMessages([
                                        'required' => 'Satuan tidak boleh kosong.',
                                    ]),
                                Select::make('kategori')
                                    ->required()
                                    ->options([
                                        'Barang Kedaluwarsa' => 'Barang Kedaluwarsa',
                                        'Barang Non-Kedaluwarsa' => 'Barang Non-Kedaluwarsa',
                                    ])
                                    ->reactive()
                                    ->validationMessages([
                                        'required' => 'Kategori tidak boleh kosong.',
                                    ]),
                                DatePicker::make('tanggal_exp')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-m-calendar-days')
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->visible(fn($get) => $get('kategori') === 'Barang Kedaluwarsa') // Kolom muncul jika kategori adalah Kedaluwarsa
                                    ->required(fn($get) => $get('kategori') === 'Barang Kedaluwarsa')
                                    ->validationMessages([
                                        'required' => 'Tanggal Kedaluwarsa tidak boleh kosong.',
                                    ]),
                            ]),
                        // ->columnSpan('full'),


                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query(StokBarang::query()->orderBy('tanggal_exp', 'asc'))
            ->columns([
                TextColumn::make('id')
                    ->label('ID Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => request()->user()->name === 'Admin Logistik'),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            // 'view' => Pages\ViewDetailBarang::route('/{record}'),

        ];
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             // Informasi Transaksi
    //             Group::make()
    //                 ->schema([
    //                     TextEntry::make('id')
    //                         ->label('ID Barang'),
    //                     TextEntry::make('nama_barang')
    //                         ->label('Nama Barang'),

    //                 ]),

    //             // Detail Barang
    //             Group::make()
    //                 ->schema([
    //                     TextEntry::make('StokBarang')
    //                         ->label('Detail Barang')
    //                         ->badge()
    //                         // ->color(function ($record) {
    //                         //     $expDate = \Carbon\Carbon::parse($record->tanggal_exp)->startOfDay(); // Mengatur waktu menjadi 00:00
    //                         //     $now = \Carbon\Carbon::now()->startOfDay(); // Mengatur waktu saat ini menjadi 00:00
    //                         //     $daysRemaining = $now->diffInDays($expDate, false); // Menghitung selisih hari dengan tanggal kedaluwarsa

    //                         //     // Cek apakah tanggal kedaluwarsa sudah lewat
    //                         //     if ($expDate->isPast() || $daysRemaining == 0) {
    //                         //         return 'danger'; // Merah jika sudah kedaluwarsa
    //                         //     }

    //                         //     // Cek jika kedaluwarsa dalam waktu 30 hari atau kurang
    //                         //     if ($daysRemaining <= 30 && $daysRemaining > 0) {
    //                         //         return 'warning'; // Kuning jika dalam 30 hari atau kurang
    //                         //     }

    //                         //     // Jika kedaluwarsa lebih dari 30 hari
    //                         //     return 'success';
    //                         // })
    //                         ->state(
    //                             fn($record) =>
    //                             $record->StokBarang->map(
    //                                 fn($detail) =>
    //                                 "{$detail->merk} $detail->tanggal_exp ({$detail->stok} {$detail->satuan})"
    //                             )->implode(', ')
    //                         )
    //                 ]),

    //         ]);
    // }
}
