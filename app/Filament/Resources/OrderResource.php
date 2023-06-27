<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->label('Order id')
                    ->hidden(Pages\CreateOrder::class),
                Select::make('customer_id')->required()
                    ->searchable()
                    ->hiddenOn(Pages\EditOrder::class)
                    ->hiddenOn(Pages\ViewOrder::class)
                    ->options(function () {
                        return User::limit(5)->pluck('name', 'id');
                    }),
                TextInput::make('customer.name')->label('customer')
                    ->hiddenOn(Pages\CreateOrder::class),
                Select::make('status')
                    ->options([
                        Order::ORDERED => 'Ordered',
                        Order::READY_FOR_DELEVIRY => 'Ready for delivery',
                        Order::PROCESSING => 'processing',
                        Order::DELEVIRED => 'delevired',
                    ]),
                Select::make('branch_id')->required()
                    ->searchable()
                    ->hiddenOn(Pages\EditOrder::class)
                    ->hiddenOn(Pages\ViewOrder::class)
                    ->options(function () {
                        return Branch::pluck('name', 'id');
                    })->columns(2),
                TextInput::make('branch.name')->label('branch')
                    ->hiddenOn(Pages\CreateOrder::class),
                Repeater::make('orderDetails')
                    ->schema([
                        Select::make('product_id')
                            ->required()
                            ->searchable()
                            ->options(function () {
                                return Product::limit(5)->pluck('name', 'id');
                            }),
                        Select::make('unit_id')
                            ->required()
                            ->searchable()
                            ->options(function () {
                                return Unit::pluck('name', 'id');
                            }),
                        TextInput::make('quantity'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order id')->toggleable(isToggledHiddenByDefault: false)
                    ->copyable()
                    ->copyMessage('Order id copied')
                    ->copyMessageDuration(1500)
                    ->sortable()->searchable()

                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('customer.name')->label('customer')->toggleable()
                    ->searchable(isIndividual: true)
                    ->tooltip(fn (Model $record): string => "By {$record->customer->name}"),
                BadgeColumn::make('status')
                    ->colors([
                        'primary',
                        'secondary' => static fn ($state): bool => $state === Order::PENDING_APPROVAL,
                        'warning' => static fn ($state): bool => $state === Order::READY_FOR_DELEVIRY,
                        'success' => static fn ($state): bool => $state === Order::DELEVIRED,
                        'danger' => static fn ($state): bool => $state === Order::PROCESSING,
                    ])
                    ->iconPosition('after'),
                TextColumn::make('total'),
                TextColumn::make('branch.name'),
                TextColumn::make('created_at')->sortable(),
                // TextColumn::make('recorded'),
                // TextColumn::make('orderDetails'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Filter::make('active')
                //     ->query(fn (Builder $query): Builder => $query->where('active', true)),
                SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options([
                        'ordered' => 'Ordered',
                        'processing' => 'Processing',
                        'ready_for_delivery' => 'Ready for deleviry',
                        'delevired' => 'Delevired',
                        'pending_approval' => 'Pending approval',
                    ]),
                SelectFilter::make('customer_id')
                    ->searchable()
                    ->multiple()
                    ->label('Customer')->relationship('customer', 'name'),
                SelectFilter::make('branch_id')
                    ->searchable()
                    ->multiple()
                    ->label('Branch')->relationship('branch', 'name'),
                Filter::make('active'),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(), 
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }


    protected function getTableReorderColumn(): ?string
    {
        return 'sort';
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return null;
    }


    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Order::search($searchQuery)->keys());
        }

        return $query;
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $model): bool
    {
        return false;
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->id;
        dd();
        return $record->this->id;
    }
}
