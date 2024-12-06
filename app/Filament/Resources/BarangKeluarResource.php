<?php

namespace App\Filament\Resources;

use stdClass;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use App\Models\StokBarang;
use Filament\Tables\Table;
use App\Models\BarangKeluar;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangKeluarResource\Pages;
use App\Filament\Resources\BarangKeluarResource\RelationManagers;


class BarangKeluarResource extends Resource
{
    protected static ?string $model = BarangKeluar::class;

    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Distribusi Barang';
    protected static ?string $modelLabel = 'Distribusi Barang';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('id')
                            ->id('id')
                            ->columnSpan(2)
                            ->default(fn() => 'OUT-' . Carbon::now()->format('dmhis'))
                            ->readOnly()
                            ->label('Id')
                            ->required(),
                        TableRepeater::make('detailBarangKeluar')
                            ->relationship('detailBarangKeluar')
                            ->label('Barang')
                            ->headers([
                                Header::make('Nama Barang')->markAsRequired(),
                                Header::make('Jumlah')->align(Alignment::Center)->markAsRequired(),
                                Header::make('Satuan')->align(Alignment::Center)->markAsRequired(),
                            ])
                            ->schema([
                                // Select::make('id_barang')
                                //     ->label('Nama Barang')
                                //     ->required()
                                //     ->relationship(name: 'barang', modifyQueryUsing: fn(Builder $query) => $query->orderBy('nama_barang'))
                                //     ->getOptionLabelFromRecordUsing(function (Barang $record) {
                                //         $expDate = $record->stok ? " (Expired " . Carbon::parse($record->exp_date)->translatedFormat('j F Y') . ")" : "";
                                //         return "{$record->name}{$expDate}";
                                //     })
                                //     ->helperText('           '),
                                Select::make('id_barang')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->reactive()
                                    ->relationship('StokBarang', 'merk', modifyQueryUsing: fn(Builder $query) => $query->orderBy('tanggal_exp', 'asc'))

                                    ->getOptionLabelFromRecordUsing(function (StokBarang $record) {
                                        $expDate = $record->tanggal_exp ? " (Expired " . Carbon::parse($record->tanggal_exp)->translatedFormat('j F Y') . ")" : "";
                                        return "{$record->merk}{$expDate}";
                                    })
                                    ->validationMessages([
                                        'required' => 'Nama barang tidak boleh kosong.',
                                    ]),
                                TextInput::make('jumlah_keluar')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->rule(function ($get) {
                                        $id = $get('id');
                                        $itemId = $get('id_barang');
                                        $itemStock = \App\Models\StokBarang::where('id', $itemId)->value('stok');
                                        $originalJumlah = \App\Models\detailBarangKeluar::where('id', $id)->value('jumlah_keluar'); // Ambil data lama
                                        $newJumlah = $get('jumlah_keluar'); // Data baru
                                        $quantityDifference = $newJumlah - $originalJumlah; // Hitung selisih jumlah

                                        // Abaikan validasi jika stok kosong dan jumlah data baru lebih kecil dari sebelumnya
                                        if ($itemStock === 0 && $newJumlah < $originalJumlah) {
                                            return null;
                                        }

                                        // Larang input jika stok tidak valid
                                        if ($itemStock === null || $itemStock < 0) {
                                            return "prohibited";
                                        }

                                        // Validasi stok kurang dari selisih quantity
                                        if ($quantityDifference > 0 && $itemStock < $quantityDifference) {
                                            return "prohibited"; // Pengecekan custom, stok tidak mencukupi
                                        }

                                        return null; // Jika validasi lolos
                                    })
                                    ->validationMessages([
                                        'prohibited' => fn($get) => 'Stok tersedia hanya ' . \App\Models\StokBarang::where('id', $get('id_barang'))->value('stok') . '.',
                                        'required' => 'Jumlah tidak boleh kosong.',
                                    ]),

                                // ->helperText(fn($get) => $get('item_id') ? 'Stok saat ini: ' . (\App\Models\Item::find($get('item_id'))->stock ?? 'Tidak tersedia') : ''),
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

                            ]),
                        // ->columnSpan('full'),

                        DatePicker::make('tanggal_distribusi')
                            ->label('Tanggal Distribusi')
                            ->columnSpan(2)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->closeOnDateSelection()
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->validationMessages([
                                'required' => 'Tanggal distribusi tidak boleh kosong.',
                            ]),
                        TextInput::make('nama_penerima')
                            ->columnSpan(2)
                            ->label('Nama Penerima')
                            ->required()
                            ->reactive()
                            ->validationMessages([
                                'required' => 'Nama penerima tidak boleh kosong.',
                            ]),
                        Textarea::make('alamat_penerima')
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 1,
                                'md' => 1,
                            ])
                            ->label('Alamat Tujuan')
                            ->required()
                            ->reactive()
                            ->validationMessages([
                                'required' => 'Alamat tujuan tidak boleh kosong.',
                            ]),
                        Select::make('status')
                            ->columnSpan(2)
                            ->label('Status')
                            ->required()
                            ->reactive()
                            ->options([
                                'proses' => 'Proses',
                                'selesai' => 'Selesai',
                            ])
                            ->default(fn($record) => $record ? $record->status : 'proses')
                            ->validationMessages([
                                'required' => 'Status tidak boleh kosong.',
                            ]),
                        FileUpload::make('foto') // Field untuk upload gambar
                            ->label('Upload Gambar')
                            ->image()
                            ->directory('img/bukti') // Simpan file di folder storage/app/public/images/barang-keluar
                            ->disk('public')
                            ->image()
                            ->maxSize(10240)
                            ->helperText('Unggah gambar terkait foto serah terima logistik. Ukuran maksimal: 10MB.')
                            ->visible(fn($get) => $get('status') === 'selesai')
                            ->required(fn($get) => $get('status') === 'selesai')
                            ->validationMessages([
                                'required' => 'Silahkan Upload Gambar.',
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
                // TextColumn::make('id'),
                TextColumn::make('tanggal_distribusi')
                    ->label('Tanggal Distribusi')
                    ->sortable()
                    ->date('d F Y'),
                TextColumn::make('nama_penerima')
                    ->label('Nama Penerima'),
                TextColumn::make('alamat_penerima')
                    ->label('Alamat Penerima')
                    ->limit(25)
                    ->searchable(),

                TextColumn::make('detailBarangKeluar')
                    ->label('Detail Barang')
                    ->limit(15)
                    ->formatStateUsing(function ($record) {
                        // dd($record->detailBarangKeluar);
                        return $record->detailBarangKeluar
                            ->map(fn($detail) => "{$detail->StokBarang->barang->nama_barang} {$detail->StokBarang->merk} ({$detail->jumlah_keluar} {$detail->StokBarang->satuan})")
                            ->implode(', ');
                    }),
                // Group::make()
                //     ->schema([
                //         TextEntry::make('detailBarangKeluar')
                //             ->label('Detail Barang')
                //             ->state(
                //                 fn($record) =>
                //                 $record->detailBarangKeluar->map(
                //                     fn($detail) =>
                //                     "{$detail->barang->nama_barang} ({$detail->jumlah_keluar} {$detail->satuan})"
                //                 )->implode(', ')
                //             ),
                //     ]),
                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'proses' => 'heroicon-o-clock',
                        'selesai' => 'heroicon-o-check-circle',
                    })->color(fn(string $state): string => match ($state) {
                        'proses' => 'warning',
                        'selesai' => 'success',
                    })
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBarangKeluars::route('/'),
            'create' => Pages\CreateBarangKeluar::route('/create'),
            'edit' => Pages\EditBarangKeluar::route('/{record}/edit'),
            'view' => Pages\ViewDetail::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Informasi Transaksi
                Group::make()
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID Transaksi'),

                        TextEntry::make('tanggal_distribusi')
                            ->label('Tanggal Distribusi')
                            ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y')),

                        TextEntry::make('nama_penerima')
                            ->label('Nama Penerima'),

                        TextEntry::make('alamat_penerima')
                            ->label('Alamat Penerima'),


                    ]),

                // Detail Barang
                Group::make()
                    ->schema([
                        TextEntry::make('detailBarangKeluar')
                            ->label('Detail Barang')
                            ->state(
                                fn($record) =>
                                $record->detailBarangKeluar->map(
                                    fn($detail) =>
                                    "{$detail->StokBarang->barang->nama_barang} {$detail->StokBarang->merk} ({$detail->jumlah_keluar} {$detail->stokBarang->satuan})"
                                )->implode(', ')
                            ),
                        ImageEntry::make('foto')
                            ->label('Bukti Gambar')
                            ->disk('public')
                            ->height(200)
                            ->width(200),

                    ]),
            ]);
    }
}
