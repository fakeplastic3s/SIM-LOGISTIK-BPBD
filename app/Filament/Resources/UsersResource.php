<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Filament\Resources\UsersResource\RelationManagers;
use App\Models\User;
use App\Models\Users;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $model = User::class;


    public static function canViewAny(): bool
    {

        return \Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpan(2)
                    ->label('Nama')
                    ->required()
                    ->reactive()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Nama sudah ada.',
                        'required' => 'Nama tidak boleh kosong.',
                    ]),
                TextInput::make('email')
                    ->columnSpan(2)
                    ->label('E-Mail')
                    ->required()
                    ->email()
                    ->reactive()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Email sudah ada.',
                        'required' => 'Email tidak boleh kosong.',
                    ]),
                Select::make('role')
                    ->columnSpan(2)
                    ->label('Role')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'kepala' => 'Kepala Bidang Kedaruratan dan Logistik',
                        'pusdalops' => 'PUSDALOPS-PB',
                    ])
                    ->validationMessages([
                        'required' => 'Role tidak boleh kosong.',
                    ]),
                TextInput::make('password')
                    ->columnSpan(2)
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->reactive()
                    ->minLength(8)
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->unique(ignoreRecord: true)
                    ->dehydrated(fn($state) => filled($state))
                    ->validationMessages([
                        'required' => 'Password tidak boleh kosong.',
                        'min' => 'Password harus lebih dari 8 karakter.'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'success',
                        'kepala' => 'info',
                        'pusdalops' => 'primary',
                    }),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
