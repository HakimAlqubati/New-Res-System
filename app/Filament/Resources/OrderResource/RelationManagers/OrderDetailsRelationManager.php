<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Hamcrest\Type\IsNumeric;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                Forms\Components\TextInput::make('purchase_invoice_id'),
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
                // IconColumn::make('negative_inventory_quantity')
                //     ->options([
                //         'heroicon-o-check' => fn ($state, $record): bool => $record->negative_inventory_quantity === 1,
                //         // 'heroicon-o-pencil' => fn ($state, $record): bool => $record->status === 2,
                //         // 'heroicon-o-clock' => fn ($state): bool => $state === 'reviewing',
                //         // 'heroicon-o-check-circle' => fn ($state): bool => $state === 'published',
                //     ])
                //     ->label(__('Ordered with negative quantity' . '?'))
                // // ->boolean()
                // ,
                // TextColumn::make('purchase_invoice_no')->label(__('lang.invoice_no')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make()->label(__('lang.change_or_add_purchase_supplier'))
                //     ->using(function (Model $record, array $data): Model {

                //         $product_qtyies = getProductQuantities($record['product_id'], $record['unit_id'], $record['id'], $data['purchase_invoice_id']);

                //         $product_price = getProductPriceByProductUnitPurchaseInvoiceId($record['product_id'], $record['unit_id'], $data['purchase_invoice_id']);
                //         if ($product_price > 0) {
                //             $data['price'] = $product_price;
                //             if ((count($product_qtyies) > 0 && $product_qtyies['remaning_qty'] >= 0)) {
                //                 $data['negative_inventory_quantity'] = false;
                //             } else {
                //                 $data['negative_inventory_quantity'] = true;
                //             }
                //             $record->update($data);
                //         }
                //         return $record;
                //     })
                //     ->before(function (
                //         EditAction $action,
                //         RelationManager $livewire,
                //         Model $record,
                //         array $data
                //     ) {
                //         $product_price = getProductPriceByProductUnitPurchaseInvoiceId($record['product_id'], $record['unit_id'], $data['purchase_invoice_id']);
                //         if ($product_price == 0) {
                //             Notification::make()
                //                 ->warning()
                //                 ->title(__('lang.there_is_no_purchase'))
                //                 ->body(__('lang.please_type_an_invoice_no_exist'))
                //                 ->persistent()

                //                 ->send();

                //             $action->halt();
                //         }
                //     })
                
                // ,
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
