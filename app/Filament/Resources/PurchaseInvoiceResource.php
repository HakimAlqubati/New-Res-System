<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseInvoiceResource\Pages;
use App\Filament\Resources\PurchaseInvoiceResource\RelationManagers;
use App\Filament\Resources\PurchaseInvoiceResource\RelationManagers\PurchaseInvoiceDetailsRelationManager;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseInvoiceResource extends Resource
{
    protected static ?string $model = PurchaseInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getLabel(): ?string
    {
        return __('lang.purchase_invoice');
    }
    protected static function getNavigationLabel(): string
    {
        return __('lang.purchase_invoice');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_no')->label(__('lang.invoice_no'))
                    ->required()
                    ->placeholder('Enter invoice number')
                    // ->extraInputAttributes(['readonly' => true])
                    ->disabledOn('edit'),
                DatePicker::make('date')
                    ->required()
                    ->placeholder('Select date')
                    ->default(date('Y-m-d'))
                    ->format('Y-m-d')
                    ->disabledOn('edit')
                    ->format('Y-m-d'),
                Select::make('supplier_id')->label(__('lang.supplier'))
                    ->options(
                        Supplier::get(['id', 'name'])->pluck('name', 'id')
                    )->searchable()
                    ->required()
                    ->disabledOn('edit'),
                Select::make('store_id')->label(__('lang.store'))
                    ->searchable()
                    ->options(
                        Store::where('active', 1)->get(['id', 'name'])->pluck('name', 'id')
                    )->required()
                    ->disabledOn('edit')
                    ->searchable(),
                Textarea::make('description')->label(__('lang.description'))
                    ->placeholder('Enter description'),
                Repeater::make('units')
                    ->columns(4)
                    ->defaultItems(1)
                    ->hiddenOn([
                        Pages\EditPurchaseInvoice::class,
                        Pages\ViewPurchaseInvoice::class
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->relationship('purchaseInvoiceDetails')
                    ->label(__('lang.purchase_invoice_details'))
                    ->orderable('product_id')
                    ->schema([
                        Select::make('product_id')
                            ->label(__('lang.product'))
                            ->required()
                            ->searchable()
                            ->disabledOn('edit')
                            ->options(function () {
                                return Product::pluck('name', 'id');
                            })->searchable(),
                        Select::make('unit_id')
                            ->label(__('lang.unit'))
                            ->required()
                            ->searchable()
                            ->disabledOn('edit')
                            ->options(function () {
                                return Unit::pluck('name', 'id');
                            })->searchable(),
                        TextInput::make('quantity')
                            ->label(__('lang.quantity'))
                            ->type('number')->default(1)
                            ->disabledOn('edit')
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)
                                    ->thousandsSeparator(',')
                            ),
                        TextInput::make('price')
                            ->label(__('lang.price'))
                            ->type('number')->default(1)
                            ->disabledOn('edit')
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)
                                    ->thousandsSeparator(',')
                            ),

                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Supplier'),
                TextColumn::make('store.name')->label('Store'),
                TextColumn::make('date')->sortable(),
                TextColumn::make('description')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseInvoiceDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseInvoices::route('/'),
            'create' => Pages\CreatePurchaseInvoice::route('/create'),
            'edit' => Pages\EditPurchaseInvoice::route('/{record}/edit'),
            'view' => Pages\ViewPurchaseInvoice::route('/{record}'),
        ];
    }


    public static function canDeleteAny(): bool
    {
        return static::can('deleteAny');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
