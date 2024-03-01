<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'User & Roles';
    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),

                // Select::make('role_id')
                //     ->label('Role')
                //     ->searchable()
                //     ->required()
                //     ->options(function () {
                //         return DB::table('roles')->pluck('name', 'id');
                //     }),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->maxItems(1)
                    ->preload()
                    ->searchable(),

                Select::make('owner_id')
                    ->label('Owner')
                    ->searchable()
                    ->options(function () {
                        return DB::table('users')->pluck('name', 'id');
                    }),

                TextInput::make('password')
                    ->password()
                    ->columnSpanFull()
                    // ->required()
                    ->required(fn (string $context) => $context === 'create')
                    ->reactive()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()->searchable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('name')
                    ->sortable()->searchable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('email')
                    ->sortable()->searchable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('owner.name')->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('active')),
                    Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
