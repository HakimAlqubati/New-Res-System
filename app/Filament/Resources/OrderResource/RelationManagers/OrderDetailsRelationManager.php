<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
                TextColumn::make('product_id')->label(__('lang.id')),
                TextColumn::make('product.name')->label(__('lang.product')),
                TextColumn::make('product.code')->label(__('lang.product_code')),
                TextColumn::make('unit.name')->label(__('lang.unit')),
                TextColumn::make('quantity')->label(__('lang.ordered_quantity_by_branch')),
                TextColumn::make('available_quantity')->label(__('lang.quantity_after_modification')),
                TextColumn::make('price')->label(__('lang.unit_price')),
                IconColumn::make('negative_inventory_quantity')
                    ->options([
                        'heroicon-o-check' => fn ($state, $record): bool => $record->negative_inventory_quantity === 1,
                        // 'heroicon-o-pencil' => fn ($state, $record): bool => $record->status === 2,
                        // 'heroicon-o-clock' => fn ($state): bool => $state === 'reviewing',
                        // 'heroicon-o-check-circle' => fn ($state): bool => $state === 'published',
                    ])
                    ->label(__('Ordered with negative quantity'))
                // ->boolean()
                ,
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
