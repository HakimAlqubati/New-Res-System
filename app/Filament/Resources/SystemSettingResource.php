<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemSettingResource\Pages;
use App\Filament\Resources\SystemSettingResource\RelationManagers;
use App\Models\SystemSetting;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SystemSettingResource extends Resource
{
    protected static ?string $model = SystemSetting::class;
    protected static ?string $slug = 'system-settings';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationLabel(): string
    {
        return __('system_settings.system_settings');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('website_name')->label(__('system_settings.website_name')),
                TextInput::make('currency_symbol')->label(__('system_settings.currency_symbol')),
                Select::make('calculating_orders_price_method')->label(__('system_settings.calculating_orders_price_method'))
                    ->options([
                        'from_unit_prices' => __('system_settings.from_unit_prices'),
                        'fifo' => __('system_settings.fifo'),
                    ])->default('from_unit_prices')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('website_name')->label(__('system_settings.website_name')),
                TextColumn::make('currency_symbol')->label(__('system_settings.currency_symbol')),
                TextColumn::make('calculating_orders_price_method')->label(__('system_settings.calculating_orders_price_method'))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSystemSettings::route('/'),
            'create' => Pages\CreateSystemSetting::route('/create'),
            'edit' => Pages\EditSystemSetting::route('/{record}/edit'),
        ];
    }
}
