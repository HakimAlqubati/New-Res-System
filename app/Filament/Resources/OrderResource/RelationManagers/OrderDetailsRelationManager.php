<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $recordTitleAttribute = 'order_id';

    public static function getTitle(): string
    {
        return __('lang.order_details');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_id')->label(__('lang.id')),
                Tables\Columns\TextColumn::make('product.name')->label(__('lang.product')),
                Tables\Columns\TextColumn::make('product.code')->label(__('lang.product_code')),
                Tables\Columns\TextColumn::make('unit.name')->label(__('lang.unit')),
                Tables\Columns\TextColumn::make('quantity')->label(__('lang.ordered_quantity_by_branch')),
                Tables\Columns\TextColumn::make('available_quantity')->label(__('lang.quantity_after_modification')),
                Tables\Columns\TextColumn::make('price')->label(__('lang.unit_price')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public   function canCreate(): bool
    {
        return false;
    }
}
