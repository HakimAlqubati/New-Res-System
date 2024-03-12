<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseInvoiceResource\Pages;
use App\Filament\Resources\PurchaseInvoiceResource\RelationManagers;
use App\Filament\Resources\PurchaseInvoiceResource\RelationManagers\PurchaseInvoiceDetailsRelationManager;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\UnitPrice;
use Closure;
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
use Illuminate\Support\Str;

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
                    ->placeholder('Enter description')
                    ->columnSpanFull(),
                Repeater::make('units')
                    ->columns(5)
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
                            })
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('unit_id', null))
                            ->searchable(),
                        Select::make('unit_id')
                            ->label(__('lang.unit'))
                            ->required()
                            ->disabledOn('edit')
                            ->options(
                                function (callable $get) {

                                    $unitPrices = UnitPrice::where('product_id', $get('product_id'))->get()->toArray();

                                    if ($unitPrices)
                                        return array_column($unitPrices, 'unit_name', 'unit_id');
                                    return [];
                                }
                            )
                            // ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state, $get) {
                                $unitPrice = UnitPrice::where(
                                    'product_id',
                                    $get('product_id')
                                )->where('unit_id', $state)->first()->price;
                                $set('price', $unitPrice);

                                $set('total_price',  $unitPrice * $get('quantity'));
                            }),
                        TextInput::make('quantity')
                            ->label(__('lang.quantity'))
                            ->required()
                            ->type('number')
                            ->default(1)
                            ->disabledOn('edit')
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)
                                    ->thousandsSeparator(',')
                            )->reactive()
                            ->afterStateUpdated(function (Closure $set, $state, $get) {
                                $set('total_price', $state * $get('price'));
                            }),
                        TextInput::make('price')
                            ->required()
                            ->label(__('lang.price'))
                            ->type('number')
                            ->default(1)
                            ->disabledOn('edit')
                            // ->mask(
                            //     fn (TextInput\Mask $mask) => $mask
                            //         ->numeric()
                            //         ->decimalPlaces(2)
                            //         ->thousandsSeparator(',')
                            // )
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state, $get) {
                                $set('total_price', $state * $get('quantity'));
                            }),
                        TextInput::make('total_price')->default(1)
                            ->extraInputAttributes(['readonly' => true]),

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
