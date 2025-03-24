<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Products - units';

    public static function getPluralLabel(): ?string
    {
        return __('lang.products');
    }
    protected static function getNavigationLabel(): string
    {
        return __('lang.products');
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return __('lang.products');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label(__('lang.name')),
                TextInput::make('code')->required()->label(__('lang.code'))
                // ->disabledOn('edit')
                ,

                Textarea::make('description')->label(__('lang.description'))
                    ->rows(2)
                // ->cols(20)
                ,
                Checkbox::make('active')->label(__('lang.active')),
                Select::make('category_id')->required()->label(__('lang.category'))
                    ->searchable()
                    ->options(function () {
                        return Category::pluck('name', 'id');
                    }),
                Repeater::make('units')->label(__('lang.units_prices'))
                    ->columns(2)
                    ->hiddenOn(Pages\EditProduct::class)
                    ->columnSpanFull()
                    ->collapsible()
                    ->relationship('unitPrices')
                    ->orderable('product_id')
                    ->schema([
                        Select::make('unit_id')
                            ->label(__('lang.unit'))
                            ->searchable()
                            ->options(function () {
                                return Unit::pluck('name', 'id');
                            })->searchable(),
                        TextInput::make('price')->type('number')->default(1)
                            ->label(__('lang.price'))
                            ->mask(
                                fn(TextInput\Mask $mask) => $mask
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('lang.id'))
                    ->copyable()
                    ->copyMessage(__('lang.product_id_copied'))
                    ->copyMessageDuration(1500)
                    ->sortable()->searchable()
                    ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('lang.name'))
                    ->toggleable()
                    ->searchable()
                    ->searchable(isIndividual: true)
                    ->tooltip(fn(Model $record): string => "By {$record->name}"),
                Tables\Columns\TextColumn::make('code')->searchable()
                    ->label(__('lang.code'))
                    ->searchable(isIndividual: true, isGlobal: false),

                Tables\Columns\TextColumn::make('description')->searchable()->label(__('lang.description')),
                Tables\Columns\TextColumn::make('category.name')->searchable()->label(__('lang.category'))
                    ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\CheckboxColumn::make('active')->label('Active?')->sortable()->label(__('lang.active')),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')->label(__('lang.active'))
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('active')),
                SelectFilter::make('category_id')
                    ->searchable()
                    ->multiple()
                    ->label(__('lang.category'))->relationship('category', 'name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
                // Tables\Actions\ForceDeleteBulkAction::make(),

                BulkAction::make('exportProductsWithUnits')
                    ->label('Export with Unit Prices')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records): BinaryFileResponse {
                        // نسحب العلاقات مع الوحدات والتصنيفات
                        $data = [];

                        foreach ($records as $product) {
                            $product->load(['unitPrices.unit', 'category']);
                            foreach ($product->unitPrices as $unitPrice) {
                                $data[] = [
                                    'product_id' => $product->id,
                                    'product_name' => $product->name,
                                    'product_code' => $product->code,
                                    'category' => $product->category?->name ?? '',
                                    'unit' => $unitPrice->unit?->name ?? '',
                                    'price' => $unitPrice->price,
                                ];
                            }
                        }

                        // توليد وتصدير Excel
                        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                            public function __construct(public array $data) {}

                            public function collection()
                            {
                                return collect($this->data);
                            }

                            public function headings(): array
                            {
                                return ['product_id', 'product_name', 'product_code', 'category', 'unit', 'price'];
                            }
                        }, 'products_with_units.xlsx');
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->color('success'),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UnitPricesRelationManager::class,
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
