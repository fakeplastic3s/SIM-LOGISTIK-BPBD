<?php

namespace App\Filament\Resources;

use stdClass;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BarangMasuk;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangMasukResource\Pages;
use App\Filament\Resources\BarangMasukResource\RelationManagers;
use App\Models\StokBarang;

class BarangMasukResource extends Resource
{
    protected static ?string $model = BarangMasuk::class;

    protected static ?string $navigationLabel = 'Barang Masuk';
    protected static ?string $modelLabel = 'Barang Masuk';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('id')
                            ->columnSpan(2)
                            ->id('id')
                            ->default(fn() => 'IN-' . Carbon::now()->format('dmhis')) // Generate random string
                            ->readOnly()
                            ->label('Item ID')
                            ->required(),

                        Select::make('id_barang')
                            ->columnSpan(2)
                            ->label('Nama Barang')
                            ->required()
                            ->reactive()
                            // ->relationship('StokBarang', 'merk')
                            ->relationship('StokBarang', 'merk', modifyQueryUsing: fn(Builder $query) => $query->orderBy('tanggal_exp', 'asc'))

                            ->getOptionLabelFromRecordUsing(function (StokBarang $record) {
                                $expDate = $record->tanggal_exp ? " (Expired " . Carbon::parse($record->tanggal_exp)->translatedFormat('j F Y') . ")" : "";
                                return "{$record->merk}{$expDate}";
                            })
                            ->validationMessages([
                                'required' => 'Nama barang tidak boleh kosong.',
                            ]),
                        DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->columnSpan(2)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->closeOnDateSelection()
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->validationMessages([
                                'required' => 'Tanggal masuk tidak boleh kosong.',
                            ]),
                        TextInput::make('jumlah_masuk')
                            ->label('Jumlah')
                            ->required()
                            ->reactive()
                            ->numeric()
                            ->validationMessages([
                                'required' => 'Jumlah tidak boleh kosong.',
                            ]),
                        Select::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->reactive()
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
                        Select::make('sumber')
                            ->label('Sumber')
                            ->columnSpan(2)
                            ->required()
                            ->reactive()
                            ->options([
                                'APBD Provinsi Jawa Tengah' => 'APBD Provinsi Jawa Tengah',
                                'APBD Kabupaten Pekalongan' => 'APBD Kabupaten Pekalongan',
                                'Masyarakat' => 'Masyarakat',

                            ])
                            ->validationMessages([
                                'required' => 'Sumber tidak boleh kosong.',
                            ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                TextColumn::make('id'),
                TextColumn::make('StokBarang.merk')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->stokBarang->barang->nama_barang . ' ' . $record->stokBarang->merk;
                    }),
                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->sortable()
                    ->date('l, d F Y'),
                TextColumn::make('jumlah_masuk')
                    ->label('jumlah'),
                TextColumn::make('satuan')
                    ->label('Satuan'),
                TextColumn::make('sumber')
                    ->label('Sumber'),
            ])
            ->filters([
                SelectFilter::make('sumber')
                    ->options([
                        'APBD Provinsi Jawa Tengah' => 'APBD Provinsi Jawa Tengah',
                        'APBD Kabupaten Pekalongan' => 'APBD Kabupaten Pekalongan',
                        'Masyarakat' => 'Masyarakat',
                    ])
                    ->label('Kategori Barang'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBarangMasuks::route('/'),
            'create' => Pages\CreateBarangMasuk::route('/create'),
            'edit' => Pages\EditBarangMasuk::route('/{record}/edit'),
        ];
    }
}
