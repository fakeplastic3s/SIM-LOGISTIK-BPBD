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
use App\Models\Barang;
use App\Models\StokBarang;

class BarangMasukResource extends Resource
{
    protected static ?string $model = BarangMasuk::class;

    protected static ?string $navigationLabel = 'Barang Masuk';
    protected static ?string $modelLabel = 'Barang Masuk';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {

        return \Auth::user()->role === 'admin' || \Auth::user()->role === 'kepala';
    }

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

                        // Select::make('id_barang')
                        //     ->columnSpan(2)
                        //     ->label('Nama Barang')
                        //     ->required()
                        //     ->reactive()
                        //     ->searchable()
                        //     ->preload()
                        //     ->relationship('StokBarang', 'merk', modifyQueryUsing: fn(Builder $query) => $query->where('tanggal_exp', '>', now())->orWhereNull('tanggal_exp')->orderBy('tanggal_exp', 'asc')->orderBy('id_barang', 'asc'))
                        //     ->getOptionLabelFromRecordUsing(function (StokBarang $record) {
                        //         $barang = Barang::find($record->id_barang);
                        //         $expDate = $record->tanggal_exp ? " (Expired " . Carbon::parse($record->tanggal_exp)->translatedFormat('j F Y') . ")" : "";
                        //         return "{$barang->nama_barang} - {$record->merk}{$expDate}";
                        //     })
                        //     ->validationMessages([
                        //         'required' => 'Nama barang tidak boleh kosong.',
                        //     ]),
                        Select::make('merk')
                            ->columnSpan(2)
                            ->label('Nama Barang')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return StokBarang::select('merk')
                                    ->distinct()
                                    ->where('tanggal_exp', '>', now())
                                    ->orWhereNull('tanggal_exp')
                                    ->orderBy('merk', 'asc')
                                    ->pluck('merk', 'merk');
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $stokBarang = StokBarang::where('merk', $value)->first();
                                $barang = Barang::find($stokBarang->id_barang);
                                $expDate = $stokBarang->tanggal_exp ? " (Expired " . Carbon::parse($stokBarang->tanggal_exp)->translatedFormat('j F Y') . ")" : "";
                                return "{$barang->nama_barang} - {$stokBarang->merk}{$expDate}";
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
                        DatePicker::make('tanggal_exp')
                            ->label('Tanggal Kedaluwarsa')
                            ->columnSpan(2)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->closeOnDateSelection()
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->validationMessages([
                                'required' => 'Tanggal kedaluwarsa tidak boleh kosong.',
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
                TextColumn::make('id'),
                TextColumn::make('merk')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->sortable()
                    // ->date('l, d F Y'),
                    ->date('d F Y'),
                TextColumn::make('tanggal_exp')
                    ->label('Tanggal Kedaluwarsa')
                    ->sortable()
                    // ->date('l, d F Y'),
                    ->date('d F Y'),
                TextColumn::make('jumlah_masuk')
                    ->label('jumlah')
                    ->formatStateUsing(function ($record) {
                        return $record->jumlah_masuk . ' ' . $record->satuan;
                    }),
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
                Tables\Actions\EditAction::make()
                    // ->tooltip('Edit Data Barang Masuk')
                    ->visible(fn() => \Auth::user()->role === 'admin')
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => \Auth::user()->role === 'admin')
                    ->label('Hapus'),



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
