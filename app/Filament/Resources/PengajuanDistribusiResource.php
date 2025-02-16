<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanDistribusiResource\Pages;
use App\Filament\Resources\PengajuanDistribusiResource\RelationManagers;
use App\Models\PengajuanDistribusi;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanDistribusiResource extends Resource
{
    protected static ?string $model = PengajuanDistribusi::class;
    protected static ?string $navigationLabel = 'Pengajuan Barang Distribusi';
    protected static ?string $modelLabel = 'Pengajuan Barang Distribusi';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('id')
                            ->id(('id'))
                            ->columnSpan(2)
                            ->default(fn() => 'REQ-' . Carbon::now()->format('dmhis'))
                            ->readOnly()
                            ->label('Id')
                            ->required(),
                        TableRepeater::make('detailPengajuan')
                            ->relationship('detailPengajuan')
                            ->label('Barang')
                            ->headers([
                                Header::make('Nama Barang')->markAsRequired(),
                                Header::make('Jumlah')->align(Alignment::Center)->markAsRequired(),
                                Header::make('Satuan')->align(Alignment::Center)->markAsRequired(),
                            ])
                            ->schema([
                                TextInput::make('nama_barang')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->reactive()
                                    ->validationMessages([
                                        'required' => 'Nama Barang tidak boleh kosong.',
                                    ]),
                                TextInput::make('jumlah_pengajuan')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->disabled(fn($get) => !$get('nama_barang'))
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

                            ]),
                        DatePicker::make('tanggal_pengajuan')
                            ->label('Tanggal Pengajuan')
                            ->columnSpan(2)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->closeOnDateSelection()
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->validationMessages([
                                'required' => 'Tanggal pengajuan tidak boleh kosong.',
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
                            ->label('Alamat')
                            ->required()
                            ->reactive()
                            ->validationMessages([
                                'required' => 'Alamat penerima tidak boleh kosong.',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(PengajuanDistribusi::query()->orderBy('tanggal_pengajuan', 'desc'))
            ->columns([
                TextColumn::make('tanggal_pengajuan')
                    ->label('Tanggal Distribusi')
                    ->sortable()
                    ->date('d F Y'),
                TextColumn::make('nama_penerima')
                    ->label('Nama Penerima'),
                TextColumn::make('alamat_penerima')
                    ->label('Alamat Penerima')
                    ->limit(25)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
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
            'index' => Pages\ListPengajuanDistribusis::route('/'),
            'create' => Pages\CreatePengajuanDistribusi::route('/create'),
            'edit' => Pages\EditPengajuanDistribusi::route('/{record}/edit'),
            'view' => Pages\ViewDetailPengajuan::route('/{record}'),
        ];
    }
}
